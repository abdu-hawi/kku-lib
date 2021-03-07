<?php

global $conn;

$genresQry = mysqli_query($conn,"SELECT * FROM `geners`");
$genres = [];
$genresArr = [];
while($fetch = mysqli_fetch_assoc($genresQry)){
    $genresArr['id'] = $fetch['id'];
    $genresArr['name'] = $fetch['name'];
    array_push($genres,$genresArr);
}