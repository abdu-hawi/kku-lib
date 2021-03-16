<?php
$conn = new mysqli("localhost","root","","book_reader")
or dir("Can't connect to db");

//----------- 1 -------------//
$result = mysqli_query($conn, "SELECT `book_id`,`rating` FROM `reviews` WHERE
                              `user_id` = 1 ");
$arrReader = [];
while ($row = mysqli_fetch_assoc($result)){
    array_push($arrReader,$row);
}
echo json_encode($arrReader)."<br><br><br><br>";//

////----------- 2 -------------//
$readerRev = []; // [[id]=>[[row1],[row2]]]
/*
 [32]=>[[row1],[row2]],
[34]=>[[row1]]
 */
foreach ($arrReader as $item){
    $result = mysqli_query($conn, "SELECT `book_id`,`rating`, `user_id` FROM `reviews` WHERE
                              `book_id` = '".$item['book_id']."' AND `rating` = '".$item['rating']."' AND `user_id` != 1");
    while ($row = mysqli_fetch_assoc($result)){
        if (array_key_exists($row["user_id"],$readerRev)){
            array_push($readerRev[$row["user_id"]],$row);
        }else{
            $readerRev[$row["user_id"]] = [$row];
        }
    }
}
echo "readerRev:<br>".json_encode($readerRev)."<br><br><br><br>";

////------------ 3 -----------//
//foreach ($readerRev as $k=>$v){
//    echo $k.":".count($v)."<br>";
//}
//
//
////------------ 4 -----------//
$rID=0;
$rCnt=0;
$sID =0;
$sCnt = 0;
$tID =0;
$tCnt = 0;
foreach ($readerRev as $k=>$v){
    if (count($v)>$rCnt){
        $rID = $k;
        $rCnt = count($v);
    }elseif (count($v)>$sCnt){
        $sID = $k;
        $sCnt = count($v);
    }elseif (count($v)>$tCnt){
        $tID = $k;
        $tCnt = count($v);
    }
}
echo "<br><br><br>id:".$rID."<br><br>";
echo "<br><br><br>id:".$sID."<br><br>";
echo "<br><br><br>id:".$tID."<br><br>";
//
//
////------------ 5 -----------//
//$result = mysqli_query($conn, "SELECT `book_id` AS `id`, `reviews`.`id` AS `rID`,`user_id`,
//       `publisher`, `num_pages`, `image_url`, `title`
//                        FROM `reviews`
//                        JOIN `books` ON `reviews`.`book_id` = `books`.`id`
//                        HAVING `reviews`.`user_id` =".$rID." LIMIT 6");
//$reR = [];
//while ($row = mysqli_fetch_assoc($result)){
//
//    array_push($reR,$row);
//}
//echo "reR5:<br>".json_encode($reR)."<br><br><br><br>";
//
//
//////------------ 6 -----------//
$reR = [];
$result = mysqli_query($conn, "SELECT `book_id` AS `id`, `reviews`.`id` AS `rID`,`user_id`, `publisher`, `num_pages`, `image_url`, `title`
                        FROM `reviews`
                        JOIN `books` ON `reviews`.`book_id` = `books`.`id`
                        HAVING `reviews`.`user_id` =".$rID." 
                        OR `reviews`.`user_id` =".$sID." 
                        OR `reviews`.`user_id` =".$tID." LIMIT 6");
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

echo "reR6:".count($reR)."<br>".json_encode($reR)."<br><br><br><br>";
