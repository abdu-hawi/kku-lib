<?php
require ("DB/db.php");

function get_recomminder($readerID, $isFull = false){
    global $conn;

    $query = mysqli_query($conn, "SELECT * FROM `reviews` WHERE `user_id`=".$readerID);
    $u = [];
    while($row = mysqli_fetch_assoc($query)){
        array_push($u,$row);
    }
    if (count($u) < 1){
        require ("DB/book.php");
        return get_book_by_user($readerID, $isFull);
    }

    $query = mysqli_query($conn, "SELECT * FROM `reviews` ");
    $matrix = [];
    while($row = mysqli_fetch_assoc($query)){
        $matrix[$row["user_id"]][$row["book_id"]] = $row["rating"];
    }
    $books = [];
    $queryBook = mysqli_query($conn, "SELECT `id` FROM `books` ");
    while($row = mysqli_fetch_assoc($queryBook)){
        $books[$row['id']] = $row["id"];
    }
    foreach ($matrix as $k=>$v){
        foreach ($books as $book){
            if (!array_key_exists($book,$v)){
                $matrix[$k][$book]=0;
            }
        }
    }
    $rec = recommindetion($matrix, $readerID);
    return get_books($rec, $isFull);
}
function cosinSim($matrix, $item, $otherItem){
    $numeatore = 0;
    $denItem = 0;
    $denOtherItem = 0;
    foreach ($matrix[$item] as $key=>$value){
        $numeatore += $value * $matrix[$otherItem][$key];
        $denItem += pow($value,2); // u1
        $denOtherItem += pow($matrix[$otherItem][$key],2); // other item
    }
    return $numeatore / ( sqrt($denItem) * sqrt($denOtherItem) );
}

function recommindetion($matrix,$item,$isUser = true){
    $numratore = []; // ["b1" => 0, "b2" =>0 ,.....bn =>0]
    $denomiratore = [];
    foreach ($matrix as $otherItem=>$itemValue){
        if ($otherItem != $item){
            if ($isUser){
                $sim = cosinSim($matrix, $item, $otherItem);
                foreach($matrix[$otherItem] as $book=>$rating){
                    if ($matrix[$item][$book] == 0){
                        if (!array_key_exists($book, $numratore)){
                            $numratore[$book] = 0;
                        }
                        $numratore[$book] += $sim * $rating;
                        if(!array_key_exists($book, $denomiratore)){
                            $denomiratore[$book] = 0;
                        }
                        $denomiratore[$book] += $sim;
                    }
                }
            }else{
                $result[$otherItem] = cosinSim($matrix, $item, $otherItem);
            }
        }
    }

    if ($isUser){
        foreach($numratore as $key=>$value){
            $result[$key] = $value/$denomiratore[$key];
        }
    }
    uasort($result, function($a,$b){
        if($a==$b) return 0;
        return ($a>$b) ? -1:1;
    }
    );
    return $result;
}

function get_books($rec, $isFull){
    $i = 1;
    $reR = [];
    global $conn;
    foreach($rec as $book_id=>$rating){
        if($i > 6 && !$isFull) {break;}
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
                $row['rate'] =  round($rowQuery['rating'] * 2) / 2;
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
    uasort($reR, function($a,$b){
        return $b['rate']<=>$a['rate'];
    }
    );
    return $reR;
}
