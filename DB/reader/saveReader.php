<?php

if (
    empty($_POST['name'])
    || empty($_POST['username'])
    || empty($_POST['password'])
    || empty($_POST['email'])
    || empty($_POST['age'])
    || empty($_POST['gender'])
) {
    die('ALL FIELDS REQUIRED');
}

require_once ('../readerAPI.php');
$n_name = trim($_POST['name']);
$n_username = trim($_POST['username']);
$n_pass = trim($_POST['password']);
$n_email = trim($_POST['email']);
$n_age = trim($_POST['age']);
$n_gender = trim($_POST['gender']);

$result = reader_add($n_name,$n_username,$n_pass,$n_email,$n_age,$n_gender, $_POST["genres"]);
closeDB();
//echo $result;
if ($result)
    header('Location:register_success.php');
else
    header('Location:register_fail.php');
