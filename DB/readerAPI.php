<?php
function reader_add($name, $username, $password, $email, $age, $gender, $genres)
{
    global $conn;
    if (empty($name) || empty($username) || empty($password) || empty($email) || empty($age) || empty($gender))
        return false;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        return false;

    mysqli_begin_transaction($conn);
    $query = mysqli_prepare($conn, "INSERT INTO `reader` 
    (reader_name, username, password, email, age, gender) VALUES (?, ?, ?, ?, ?, ?)");
    if($query === FALSE) {
        mysqli_rollback($conn);
        return false;
    }
//        die(mysqli_error($handle));

    mysqli_stmt_bind_param($query, "ssssis",$name, $username, $password, $email, $age, $gender);

    if(!mysqli_stmt_execute($query)) {
        mysqli_rollback($conn);
        return false;
    }
//        die(mysqli_stmt_error($query));

    $id = $query->insert_id;
    foreach ($genres as $genre){
        $query = mysqli_prepare($conn, "INSERT INTO `reader_genres` (`reader_id`, `genres_id`) VALUES (?, ?)");
        if($query === FALSE) {
            mysqli_rollback($conn);
            return false;
        }
        mysqli_stmt_bind_param($query, "ii",$id, $genre);

        if(!mysqli_stmt_execute($query)) {
            mysqli_rollback($conn);
            return false;
        }
    }
    mysqli_commit($conn);
    return $id;
}

function reader_get($username,$password){
    global $conn;
    if (empty($username) || empty($password))
        return false;

    $result = mysqli_query($conn, "SELECT * FROM `reader` WHERE
                              `username` = '$username' AND `password` = '$password'");
    $row = mysqli_fetch_row($result);
    if($row == null)
        return false;
    return $row;
}

require_once ('db.php');