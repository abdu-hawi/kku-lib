<?php 

$start = microtime(true);
get_reating();
echo "total:".(microtime(TRUE) - $start);

function get_reating(){
	$conn = new mysqli("localhost","root","","book_reader") or dir("Can't connect to db");
	$book = mysqli_query($conn,"SELECT user_id, book_id, rating, username, title FROM `reviews`
						JOIN `reader` ON `reader`.`reader_id` = `reviews`.`user_id`
						JOIN `books` ON `books`.`id` = `reviews`.`book_id`");
	mysqli_close($conn);
	$matrix = array();
	while ($row = mysqli_fetch_assoc($book)){
		$matrix[$row["username"]][$row["title"]] = $row["rating"];
	}
	// echo "<pre>";
	// print_r($matrix);
	// echo "</pre>";
	
	//hill.chandler
	echo "<pre>";
	print_r(get_recommendation($matrix,"hill.chandler"));
	echo "</pre>";
}

//-------- get recommendation
function get_recommendation($matrix,$reader){
	$total = array();
	$simSums = array();
	$ranks = array();
	foreach($matrix as $otherReader=>$value){
		if( $otherReader != $reader){
			$sim = similarity_distance($matrix,$reader,$otherReader);
			// echo "r: $otherReader | s: $sim <br>";
			foreach( $matrix[$otherReader] as $book=>$rating ){
				if ( !array_key_exists($book,$matrix[$reader])  ){
					if ( !array_key_exists($book,$total) ){
						$total[$book] = 0;
					}
					$total[$book] += $matrix[$otherReader][$book] * $sim;
					if ( !array_key_exists($book,$simSums) ){
						$simSums[$book] = 0;
					}
					$simSums[$book] += $sim;
				}
			}
		}
	}
	
	foreach($total as $key=>$value){
		$ranks[$key] = $value / $simSums[$key];
	}
	array_multisort($ranks,SORT_DESC);
	
	return $ranks;
	
}

//---------- similarity distance
function similarity_distance($matrix,$reader,$otherReader){
	$similarity = [];
	$sum=0;
	// foreach($matrix[$reader] as $key=>$value){
		// if( array_key_exists($key,$matrix[$otherReader]) ){
			// $similarity[$key] = 1;
		// }
	// }
	// if ($similarity == 1){ return 0;}
	foreach($matrix[$reader] as $key=>$value){
		if( array_key_exists($key,$matrix[$otherReader]) ){
			$sum += pow($value-$matrix[$otherReader][$key] , 2);
		}
	}
	return 1/(1+sqrt($sum));
}

?>

