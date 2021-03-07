<?php

$handle = fopen("../../kau/json/goodreads_book_authors.json", "r");
if (!$handle){
    echo "can't open file";
    return;
}
$i = 0;
$arr = [];
while (($line = fgets($handle)) !== false){
    $contents = json_decode($line,true);
    if ($contents["author_id"] == 3039530){
        break;
    }
    array_push($arr,$contents["name"]);
}
fclose($handle);
echo json_encode($arr);
