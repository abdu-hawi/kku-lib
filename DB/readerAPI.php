<?php
function reader_add($name, $username, $password, $email, $age, $gender)
{
    global $conn;
    if (empty($name) || empty($username) || empty($password) || empty($email) || empty($age) || empty($gender))
        return false;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        return false;

    $query = mysqli_prepare($conn, "INSERT INTO `reader` 
    (reader_name, username, password, email, age, gender) VALUES (?, ?, ?, ?, ?, ?)");
    if($query === FALSE)
        return false;
//        die(mysqli_error($handle));

    mysqli_stmt_bind_param($query, "ssssis",$name, $username, $password, $email, $age, $gender);

    if(!mysqli_stmt_execute($query))
        return false;
//        die(mysqli_stmt_error($query));

    return $query->insert_id;
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