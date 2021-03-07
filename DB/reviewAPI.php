<?php
require_once ('db.php');
function review_add($user_id, $book_id, $rating, $review_text){
    global $conn;
    if (empty($user_id) || empty($book_id) || empty($rating) || empty($review_text))
        return "$user_id, $book_id, $rating, $review_text";

    $query = mysqli_prepare($conn, "INSERT INTO `reviews` 
    (user_id, book_id, rating, review_text) VALUES (?, ?, ?, ?)");
    if($query === FALSE)
        return mysqli_error($conn);

    if (!mysqli_stmt_bind_param($query, "iiis",$user_id, $book_id, $rating, $review_text))
        return mysqli_stmt_error($query);

    if(!mysqli_stmt_execute($query))
        return mysqli_stmt_error($query);

    return true;
}