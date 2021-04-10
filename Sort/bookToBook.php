<?php

$conn = new mysqli("localhost","root","","book_reader")
or dir("Can't connect to db");

$query = mysqli_query($conn, "SELECT * FROM `reviews` ");
/*
    matrix = [
                "b1" => ["u1"="rate", "u2"=>"rate", ...., "b100"=>"rate"]
                "b2" => ["u1"="rate", "u2"=>"rate", ...., "b100"=>"rate"]
             ]
    */
$matrix = [];
while($row = mysqli_fetch_assoc($query)){
    $matrix[$row["book_id"]][$row["user_id"]] = $row["rating"];
}

$readers = [];
$queryBook = mysqli_query($conn, "SELECT `reader_id` FROM `reader` ");
while($row = mysqli_fetch_assoc($queryBook)){
    $readers[$row['reader_id']] = $row["reader_id"];
}
foreach ($matrix as $k=>$v){
    foreach ($readers as $reader){
        if (!array_key_exists($reader,$v)){
            $matrix[$k][$reader]=0;
        }
    }
}
$result = [];
$book = 10;
foreach ($matrix as $book_id=>$arrayOfUser){
    if ($book_id != $book) { // book number
        $numeatore = 0;
        $denItem = 0;
        $denOtherItem =0;
        foreach ($matrix[$book] as $reader_id=>$rate){
            $numeatore += $rate * $matrix[$book_id][$reader_id];
            $denItem += pow($rate,2); // u1
            $denOtherItem += pow($matrix[$book_id][$reader_id],2); // other item
        }
        $result[$book_id] = $numeatore / ( sqrt($denItem) * sqrt($denOtherItem) );
    }
}
uasort($result,function ($a,$b){
   return $b<=>$a;
});
echo "<pre>";
print_r($result);
echo "</pre>";
