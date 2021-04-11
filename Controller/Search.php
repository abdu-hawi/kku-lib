<?php
if(isset($_POST['search'])){
    include ("../DB/db.php");
    global $conn;
    $searchText = mysqli_real_escape_string($conn,$_POST['search']);
    $sql = "SELECT id,title FROM books where title like '%".$searchText."%' order by title asc limit 5";

    $result = mysqli_query($conn,$sql);

    $search_arr = array();

    while($fetch = mysqli_fetch_assoc($result)){
        $id = $fetch['id'];
        $title = $fetch['title'];

        $search_arr[] = array("id" => $id, "title" => $title);
    }
    //closeDB();
    echo json_encode($search_arr);
}else{
    echo json_encode(["id"=>0,"title"=>"nothing"]);
}