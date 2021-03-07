<?php
// last book id insert = 16250764
require_once ("../Faker/autoload.php");
$faker = Faker\Factory::create();

$handle = fopen("../../kau/json/goodreads_reviews_spoiler_raw.json", "r");
if (!$handle){
    echo "can't open file";
    return;
}

require_once ("../DB/readerAPI.php");
require_once ("../DB/reviewAPI.php");
$i = 0;
for (;$i<102;$i++){
    $s = ["male","female"];
    $s_r = rand(0,1);
    $gender = $s[$s_r];
    $age = rand(19,65);
    $userName =  $faker->userName;
    $name =  $faker->name;
    $email =  $faker->email;

    $user_id_db =  reader_add($name,$userName,"123",$email,$age,$gender);
    $rand_rev = rand(1,20);
    $r = 0;
    while (($line = fgets($handle)) !== false && $r<$rand_rev){
        $contents = json_decode($line,true);
        if ($contents["book_id"] == 16250764 ){
            echo "d".$contents["book_id"]."<br>";
            echo $i."<br>";
            fclose($handle);
            die();
        }
        $book_id_db_rand = rand(1,100);
        $res = review_add($user_id_db,$book_id_db_rand,$contents["rating"],$contents["review_text"]);
        if ($res){
            echo "true: $res <br>";
        }else{
            echo "res: $res <br>";
        }
        $r++;
    }
}
closeDB();
fclose($handle);