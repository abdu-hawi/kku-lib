<?php
if (isset($_SESSION['userinfo']) && $_SESSION['userinfo'] != false){
    // require ("DB/compare.php");
    // $books = getBook($_SESSION['userinfo'][0]);
	require("Sort/cosin.php");
	$books = get_recomminder($_SESSION['userinfo'][0]);
}else{
    require ("DB/book.php");
    $books = get_book();
}
// require ("DB/db.php");
require_once ("geners.php");



