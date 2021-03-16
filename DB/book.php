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
    $id = rand(7,94);
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
        HAVING `books`.`id` $min_max[$r_m_m] $id 
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
        $arr['rate']=$fetch['rate'];
//        $arr['count']=$fetch['count'];
        array_push($books,$arr);
        $i++;
    }
    return $books;
}
/*
 SELECT `books`.`id` AS `id`, `link`, `publisher`, `num_pages`, `image_url`, `title`, `name` AS `author`, AVG(`rating`) AS `rating`, COUNT(`rating`) AS `count` FROM `books` LEFT JOIN `author_book` ON `books`.`id` = `author_book`.`book_id`
    INNER JOIN authors ON `author_book`.`author_id` = authors.id
    INNER JOIN `reviews` ON `books`.`id` = `reviews`.`book_id`
    GROUP BY `books`.`id`
 */

/*
 SELECT `books`.`id` AS `id`, `link`, `publisher`, `num_pages`, `image_url`, `title`, `name` AS `author`, AVG(`rating`) AS `rating`, COUNT(`rating`) AS `count` FROM `books` LEFT JOIN `author_book` ON `books`.`id` = `author_book`.`book_id`
    INNER JOIN authors ON `author_book`.`author_id` = authors.id
    INNER JOIN `reviews` ON `books`.`id` = `reviews`.`book_id`
    GROUP BY `books`.`id`
    HAVING `books`.`id` < 50
    ORDER BY `books`.`id` DESC
    LIMIT 6
 */