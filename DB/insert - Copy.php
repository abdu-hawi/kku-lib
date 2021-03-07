<?php

require_once ('db.php');

function insertAuthor($name){
    global $handle;
    if (empty($name))
        return false;

    $query = mysqli_prepare($handle, "INSERT INTO `authors` (name) VALUES (?)");
    if($query === FALSE)
        return false;
    mysqli_stmt_bind_param($query, "s",$name);

    if(!mysqli_stmt_execute($query))
        return false;
    return mysqli_stmt_insert_id($query);
}

function insertGenres($name){
    global $handle;
    if (empty($name))
        return false;

    $query = mysqli_prepare($handle, "INSERT INTO `geners` (name) VALUES (?)");
    if($query === FALSE)
        return false;
    mysqli_stmt_bind_param($query, "s",$name);

    if(!mysqli_stmt_execute($query))
        return false;
    return mysqli_stmt_insert_id($query);
}



function insertBook($link, $publisher, $num_pages, $image_url, $title, $genIDs, $authIDs){
	
    global $conn;

    mysqli_begin_transaction($conn);
    try {
        $query = mysqli_prepare($conn, "INSERT INTO `books` (link, publisher, num_pages, image_url, title) 
                                    VALUES (?, ?, ? , ? ,?)");
        if($query === FALSE){
            mysqli_rollback($conn);
            return false;
        }

        mysqli_stmt_bind_param($query, "ssiss",$link, $publisher, $num_pages, $image_url, $title);

        if(!mysqli_stmt_execute($query)){
            mysqli_rollback($conn);
            return false;
        }

        $id =  mysqli_stmt_insert_id($query);
        foreach ($authIDs as $aID){
			if (!(  $authQuery = mysqli_prepare($conn, "INSERT INTO `author_book` (book_id, author_id) 
				VALUES (?, ?)") )){
				echo "Prepare failed: (" . $conn->errno . ") " . $conn->error . "<br>";
			}
			if (!(  mysqli_stmt_bind_param($authQuery, "ii",$id, $aID)  )){
				echo "Binding parameters failed: (" . $authQuery->errno . ") " . $authQuery->error . "<br>";
			}
			if (!(mysqli_stmt_execute($authQuery))){
				echo "Execute failed: (" . $authQuery->errno . ") " . $authQuery->error . "<br>";
			}
		}
        foreach ($genIDs as $gID){
			if (!( $genQuery = mysqli_prepare($conn, "INSERT INTO `book_geners` (book_id, genres_id) 
				VALUES (?, ?)") )){
				echo "Prepare failed: (" . $conn->errno . ") " . $conn->error . "<br>";					
			}
			if (!( mysqli_stmt_bind_param($genQuery, "ii",$id, $gID) )){
				echo "Binding parameters failed: (" . $genQuery->errno . ") " . $genQuery->error . "<br>";
			}
			
			if(!( mysqli_stmt_execute($genQuery) )){
				echo "Execute failed: (" . $genQuery->errno . ") " . $genQuery->error . "<br>";
			}
		}
        mysqli_commit($conn);
    }catch (Exception $exception){
        mysqli_rollback($conn);
        return false;
    }

    return true;
}