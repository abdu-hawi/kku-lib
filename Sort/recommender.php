<?php 
require ("DB/db.php");
function get_recomminder($readerID){
	global $conn;
	$query = mysqli_query($conn, "SELECT * FROM `reviews` ");
	// mysqli_close($conn);
	$matrix = array();
	while($row = mysqli_fetch_assoc($query)){
		$matrix[$row["user_id"]][$row["book_id"]] = $row["rating"];
	}
	
	$rec = getRecomminder($matrix, $readerID);
	return get_books($rec);
}

function get_books($rec){
	$i = 1;
	$reR = [];
	global $conn;
	foreach($rec as $book_id=>$rating){
		if($i > 6) {break;}
		$result = mysqli_query($conn, "SELECT `id`, `publisher`, `num_pages`, `image_url`, `title`
                        FROM `books` WHERE `id` = $book_id");
		while ($row = mysqli_fetch_assoc($result)){
			///// authors ///////
			$authors = [];
			$query = mysqli_query($conn,"SELECT `name` FROM `author_book`
					JOIN `authors` ON `author_book`.`author_id` = `authors`.`id`
					WHERE `author_book`.`book_id` = ".$row["id"]);
			while ($rowQuery = mysqli_fetch_assoc($query)){
				array_push($authors,$rowQuery["name"]);
			}
			$row['authors'] =  $authors;
			///  rating ////////////
			$query = mysqli_query($conn,"SELECT AVG(`rating`) AS `rating` FROM `reviews` WHERE `book_id` = ".$row["id"]);
			while ($rowQuery = mysqli_fetch_assoc($query)){
				$row['rate'] =  round($rowQuery['rating'],2);
			}
			// --------------------- genres
			$query = mysqli_query($conn,"SELECT * FROM `book_geners`
					JOIN `geners` ON `book_geners`.`genres_id` = `geners`.`id`
					HAVING `book_id` = ".$row["id"]);
			$genres = [];
			while ($rowQuery = mysqli_fetch_assoc($query)){
				array_push($genres,$rowQuery["name"]);
			}
			$row['genres'] =  $genres;
			array_push($reR,$row);
		}
		$i++;
	}
	// mysqli_close($conn);
	return $reR;
}


function getRecomminder($matrix,$reader){
	$numrator = array();
	$denomiratore = array();
	$result = array();
	foreach($matrix as $otherReader=>$value){
		if ($otherReader != $reader){
			$sim = similitry_distance($matrix,$reader,$otherReader);
			// echo "otherReader: $otherReader | sim: $sim <br>";
			foreach($matrix[$otherReader] as $book=>$rating){
				if (!array_key_exists($book,$matrix[$reader])){
					if(!array_key_exists($book, $numrator)){
						$numrator[$book] = 0;
					}
					$numrator[$book] += $sim * $matrix[$otherReader][$book];
					if(!array_key_exists($book, $denomiratore)){
						$denomiratore[$book] = 0;
					}
					$denomiratore[$book] += $sim;
				}
			}
		}
	}
	
	foreach($numrator as $key=>$value){
		$result[$key] = $value/$denomiratore[$key];
	}
	uasort($result, function($a,$b){
		if($a==$b) return 0;
		return ($a>$b) ? -1:1;
	}		
	);
	return $result;
}

function similitry_distance($matrix,$reader,$otherReader){
	$sum = 0 ;
	foreach($matrix[$reader] as $key=>$value){
		if (array_key_exists($key,$matrix[$otherReader])){
			$sum += pow( $value - $matrix[$otherReader][$key] , 2);
		}
	}
	return sqrt(1/(1+$sum));
}

?>

