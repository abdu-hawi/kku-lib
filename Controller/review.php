<?php
require_once ("../DB/reviewAPI.php");
if( review_add($_POST["user_id"], $_POST["book_id"], $_POST["rate"], $_POST["review_text"]) )
    echo json_encode(["result"=>"true"]);
else
    echo json_encode(["result"=>"false"]);
