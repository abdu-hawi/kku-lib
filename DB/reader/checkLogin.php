<?php
require_once ('../session.php');
if (
    empty($_POST['username']) || empty($_POST['password'])
) {
    die('ALL FIELDS REQUIRED');
}

require_once ('../readerAPI.php');
$n_name = trim($_POST['username']);
$n_pass = trim($_POST['password']);
$result = reader_get($n_name,$n_pass);
closeDB();
if (!$result){
    die("BAD ACCESS");
}else{
    $_SESSION['userinfo'] = $result;
    header ('Location:../../');
}