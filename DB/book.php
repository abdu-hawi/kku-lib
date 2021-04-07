<?php

require_once ("db.php");

function get_book_by_id($id){
    global $conn;

    $sql = "SELECT `books`.`id` AS `id`, `link`, `publisher`, `description`, `num_pages`,
       `image_url`, `title`, AVG(`rating`) AS `rate`, COUNT(`rating`) AS `count`
        FROM `books` 
            INNER JOIN `reviews` ON `books`.`id` = `reviews`.`book_id` 
            GROUP BY `books`.`id` HAVING `books`.`id` = ".$id;
    $result = mysqli_query($conn,$sql);
    $arr = [];
    while($fetch = mysqli_fetch_assoc($result)){
        $arr['id'] = $fetch['id'];
        $arr['link'] = $fetch['link'];
        $arr['publisher'] = $fetch['publisher'];
        $arr['description']=$fetch['description'];
        $arr['num_pages'] = $fetch['num_pages'];
        $arr['image_url'] = $fetch['image_url'];
        $arr['title'] = $fetch['title'];
        $arr['rate']=$fetch['rate'];
        $arr['count']=$fetch['count'];
    }
    $sql = "SELECT `name` FROM `author_book` 
        JOIN `authors` ON `author_book`.`author_id` = `authors`.`id`
        WHERE `author_book`.`book_id` = ".$id;
    $result = mysqli_query($conn,$sql);
    $a=0;
    $arr['authors'][$a] = "";
    while($fetch = mysqli_fetch_assoc($result)) {
        $arr['authors'][$a] = $fetch['name'];
        $a++;
    }
    $sql = "SELECT `name` FROM `book_geners` 
        JOIN `geners` ON `book_geners`.`genres_id` = `geners`.`id`
        WHERE `book_geners`.`book_id` = ".$id;
    $result = mysqli_query($conn,$sql);
    $a=0;
    while($fetch = mysqli_fetch_assoc($result)) {
        $arr['genres'][$a] = $fetch['name'];
        $a++;
    }
    $sql = "SELECT `rating`, `review_text`,`username`  FROM `reviews` 
JOIN `reader` ON `reviews`.`user_id` = `reader`.`reader_id`
WHERE `reviews`.`book_id` = $id LIMIT 10 ";
    $result = mysqli_query($conn,$sql);
    $a=0;
    $rev = [];
    while($fetch = mysqli_fetch_assoc($result)) {
        $rev["rating"] = $fetch['rating'];
        $rev["review_text"] = $fetch['review_text'];
        $rev["username"] = $fetch['username'];
        $arr["reviews"][$a] = $rev;
        $a++;
    }
    return $arr;
}
function get_book(){
    global $conn;
    $id = rand(27,64);
    $min_max = [">","<"];
    $r_m_m = rand(0,1);
    $desc_asc = ["DESC","ASC"];
    $r_desc_asc = rand(0,1);
    $sql = "SELECT `books`.`id` AS `id`, `link`, `publisher`, `num_pages`, `image_url`, `title`,
       `geners`.`name` AS `genres`, `authors`.`name` AS `author`, AVG(`rating`) AS `rate`,
       COUNT(`rating`) AS `count` 
        FROM `books` 
            LEFT JOIN `author_book` ON `books`.`id` = `author_book`.`book_id` 
            INNER JOIN authors ON `author_book`.`author_id` = authors.id 
            INNER JOIN `reviews` ON `books`.`id` = `reviews`.`book_id` 
            LEFT JOIN `book_geners` ON `books`.`id` = `book_geners`.`book_id` 
            INNER JOIN `geners` ON `book_geners`.`genres_id` = `geners`.`id` 
        GROUP BY `books`.`id` 
        HAVING `books`.`id` $min_max[$r_m_m] $id AND rate > 4
        ORDER BY `books`.`id` $desc_asc[$r_desc_asc]
        LIMIT 6";
    $result = mysqli_query($conn,$sql);
    $arr = [];
    $books = [];
    $i=0;
    while($fetch = mysqli_fetch_assoc($result)){
        $arr['id'] = $fetch['id'];
//        $arr['link'] = $fetch['link'];
        $arr['publisher'] = $fetch['publisher'];
        $arr['num_pages'] = $fetch['num_pages'];
        $arr['image_url'] = $fetch['image_url'];
        $arr['title'] = $fetch['title'];
        $arr['authors']=$fetch['author'];
        $arr['genres']=$fetch['genres'];
        $arr['rate']=round($fetch['rate'] * 2) / 2;
//        $arr['count']=$fetch['count'];
        array_push($books,$arr);
        $i++;
    }
    uasort($books, function($a,$b){
        return $b['rate']<=>$a['rate'];
    }
    );
    return $books;
}
function get_book_by_user($id){
    global $conn;

    $result = mysqli_query($conn,"SELECT `genres_id` FROM `reader_genres` WHERE `reader_id` = ".$id);
    $arr = [];
    $genStr = "HAVING "; // HAVING `genres_id` = 10 OR   `genres_id` = 1 OR `genres_id` = 8
    if (mysqli_num_rows($result) < 1){
        require ("DB/book.php");
        return get_book();
    }
    $numRow = mysqli_num_rows($result); // 3
    $cnt = 1;
    while($fetch = mysqli_fetch_assoc($result)){
        if ($cnt == $numRow){
            $genStr = $genStr."`genres_id` = ".$fetch["genres_id"];
        }else {
            $genStr = $genStr . "`genres_id` = " . $fetch["genres_id"] . " OR  ";
        }
        $cnt++; // 3
    }

    $result = mysqli_query($conn, "SELECT `books`.`id` AS `id`, `publisher`, `num_pages`, `image_url`, `title`, `genres_id`, ROUND(AVG(`rating`)*2)/2 AS `rate`
       FROM `books`
       LEFT JOIN `book_geners` ON `book_geners`.`book_id` = `books`.`id`
       INNER JOIN `reviews` ON `reviews`.`book_id` = `books`.`id`
       GROUP BY `id`
       ".$genStr."
       ORDER BY `rating` DESC
       LIMIT 6");
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

        // --------------------- genres
        $query = mysqli_query($conn,"SELECT * FROM `book_geners`
					JOIN `geners` ON `book_geners`.`genres_id` = `geners`.`id`
					HAVING `book_id` = ".$row["id"]);
        $genres = [];
        while ($rowQuery = mysqli_fetch_assoc($query)){
            array_push($genres,$rowQuery["name"]);
        }
        $row['genres'] =  $genres;
        array_push($arr,$row);
    }

    return $arr;
}

function get_book_by_genres($id){
    global $conn;
    $arr = [];
    $result = mysqli_query($conn, "SELECT `books`.`id`, `publisher`, `num_pages`, `image_url`, `title`, `genres_id`
                        FROM `books` 
                        JOIN `book_geners` ON `book_geners`.`book_id` = `books`.`id`
                        HAVING `genres_id` = ".$id);
    while ($row = mysqli_fetch_assoc($result)){
        ///// authors ///////
        $authors = [];
        $query = mysqli_query($conn,"SELECT `name` FROM `author_book`
					JOIN `authors` ON `author_book`.`author_id` = `authors`.`id`
					WHERE `author_book`.`book_id` = ".$row["id"]);
        while ($rowQuery = mysqli_fetch_assoc($query)){
            if (!in_array($rowQuery["name"],$authors))
                array_push($authors,$rowQuery["name"]);
        }
        $row['authors'] =  $authors;
        ///  rating ////////////
        $query = mysqli_query($conn,"SELECT ROUND(AVG(`rating`) * 2)/2 AS `rating` FROM `reviews` WHERE `book_id` = ".$row["id"]);
        while ($rowQuery = mysqli_fetch_assoc($query)){
            $row['rate'] =  round($rowQuery['rating']);
        }
        // --------------------- genres
        $query = mysqli_query($conn,"SELECT * FROM `book_geners`
					JOIN `geners` ON `book_geners`.`genres_id` = `geners`.`id`
					HAVING `book_id` = ".$row["id"]);
        $genres = [];
        while ($rowQuery = mysqli_fetch_assoc($query)){
            if (!in_array($rowQuery["name"],$genres))
//                array_push($genres,$rowQuery["name"]);
                $genres[$rowQuery["id"]] = $rowQuery["name"];
        }
        $row['genres'] =  $genres;
        if (!in_array($row,$arr))
            array_push($arr,$row);
    }
    uasort($arr, function($a,$b){
        return $b['rate']<=>$a['rate'];
    }
    );
    return $arr;
}
