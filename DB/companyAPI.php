<?php
function company_add($name,$username,$password,$email){
    global $conn;
    if (empty($name) || empty($username) || empty($password) || empty($email))
        return false;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        return false;

    $query = mysqli_prepare($conn, "INSERT INTO `company` 
    (company_name, username, password, email) VALUES (?, ?, ?, ?)");

    if($query === FALSE)
        return false;
/*
 * s = string
 * d = double
 * i = integer
 * b = blob
 */
    mysqli_stmt_bind_param($query, "ssss",$name, $username, $password, $email);

    if(!mysqli_stmt_execute($query))
        return false;

    return true;
}

function company_get($username,$password){
    global $conn;
    if (empty($username) || empty($password))
        return false;

    $result = mysqli_query($conn, "SELECT * FROM `company` WHERE
                              `username` = '$username' AND `password` = '$password'");
    $row = mysqli_fetch_row($result);
    if($row == null)
        return false;
    return $row;
}

require_once ('db.php');