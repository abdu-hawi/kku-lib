<?php
if (
    empty($_POST['publish'])
    || empty($_POST['username'])
    || empty($_POST['password'])
    || empty($_POST['email'])
) {
    die('ALL FIELDS REQUIRED');
}

require_once ('../companyAPI.php');
$n_publish = trim($_POST['publish']);
$n_name = trim($_POST['username']);
$n_pass = trim($_POST['password']);
$n_email = trim($_POST['email']);

$result = company_add($n_publish,$n_name,$n_pass,$n_email);
closeDB();

if ($result)
    header('Location:register_success.php');
else
    header('Location:register_fail.php');
