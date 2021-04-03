<?php

require_once ("../DB/db.php");

echo "<pre>";
print_r(get_book_by_user(107));
echo "<pre>";


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

    $result = mysqli_query($conn, "SELECT `books`.`id` AS `id`, `publisher`, `num_pages`, `image_url`, `title`, `genres_id`, AVG(`rating`) AS `rate`
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