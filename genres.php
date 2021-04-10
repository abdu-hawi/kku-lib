<?php
include ("DB/session.php");
require_once ("DB/db.php");
require_once ("Controller/geners.php");
require_once ("DB/book.php");

$books = get_book_by_genres($_GET["genres"]);

//echo "<pre>";
//print_r($books);
//echo "</pre>";
//exit();

include ("booksAndGenres.php");
