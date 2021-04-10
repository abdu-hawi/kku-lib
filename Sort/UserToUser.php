<?php
require ("../DB/db.php");

$get = get_recomminder(4);
echo "<pre>";
print_r($get);
echo "</pre>";
?>
    <table border="1" style="text-align: center">
        <thead>
        <tr>
            <td width="70"></td>
            <?php
            foreach ($get["books"] as $book){
                echo "<td width='70'>B$book</td>";
            }
            ?>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($get["matrix"] as $key=>$value){
            echo "<tr>";
            echo "<td>$key</td>";
            foreach ($get["books"] as $book){
                foreach ($value as $k=>$item) {
                    if ($book == $k) {
                        $c = "white";
                        if ($item == 3) $c = "orange";
                        if ($item > 3) $c = "green";
                        if ($item < 3 && $item != 0) $c = "red";
                        echo "<td style='background: $c'>$item</td>";
                    }
                }
            }
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
<?php
function get_recomminder($readerID){
    global $conn;

    $query = mysqli_query($conn, "SELECT * FROM `reviews` ");
    $matrix = [];
    while($row = mysqli_fetch_assoc($query)){
        $matrix[$row["user_id"]][$row["book_id"]] = $row["rating"];
    }
    $books = [];
    $queryBook = mysqli_query($conn, "SELECT `id` FROM `books` ");
    while($row = mysqli_fetch_assoc($queryBook)){
        $books[$row['id']] = $row["id"];
    }
    foreach ($matrix as $k=>$v){
        foreach ($books as $book){
            if (!array_key_exists($book,$v)){
                $matrix[$k][$book]=0;
            }
        }
    }
//    return["matrix"=>$matrix,"books"=>$books]; // لمشاهدة الماتركس
//    return recommindetion($matrix, $readerID); // لمشاهدة اقرب كتب الى المستخدم
    $rec = recommindetion($matrix, $readerID);
    return get_books($rec); // لارجاع بيانات الكتب
}
function cosinSim($matrix, $item, $otherItem){
    $numeatore = 0;
    $denItem = 0;
    $denOtherItem = 0;
    /*
    matrix = [
                "u1" => ["b1"="rate", "b2"=>"rate", ...., "b100"=>"rate"]
                "u2" => ["b1"="rate", "b2"=>"rate", ...., "b100"=>"rate"]
             ]
    */
    //                        key = book_id
    foreach ($matrix[$item] as $book_id=>$rate){
        $numeatore += $rate * $matrix[$otherItem][$book_id];
        $denItem += pow($rate,2); // u1
        $denOtherItem += pow($matrix[$otherItem][$book_id],2); // other item
    }
    return $numeatore / ( sqrt($denItem) * sqrt($denOtherItem) );
}

function recommindetion($matrix,$item,$isUser = true){
    $numratore = []; // ["b1" => 0, "b2" =>0 ,.....bn =>0]
    $denomiratore = [];
    /*
    matrix = [
                "u1" => ["b1"="rate", "b2"=>"rate", ...., "b100"=>"rate"]
                "u2" => ["b1"="rate", "b2"=>"rate", ...., "b100"=>"rate"]

                "u1" => ["b1"="2", "b2"=>"0", "b3"=>"0", ...., "b100"=>"rate"]
                "u2" => ["b1"="4", "b2"=>"5", "b3"=>"2", ...., "b100"=>"rate"]
                "u3" => ["b1"="4", "b2"=>"2", "b3"=>"5", ...., "b100"=>"rate"]
             ]
    */
    foreach ($matrix as $otherItem=>$itemValue){
        if ($otherItem != $item){
            if ($isUser){
                $sim = cosinSim($matrix, $item, $otherItem);
                foreach($matrix[$otherItem] as $book=>$rating){
                    if ($matrix[$item][$book] === 0){
                        if (!array_key_exists($book, $numratore)){
                            $numratore[$book] = 0;
                        }
                        $numratore[$book] += $sim * $rating;
                        if(!array_key_exists($book, $denomiratore)){
                            $denomiratore[$book] = 0;
                        }
                        $denomiratore[$book] += $sim;
                    }
                }
            }else{
                $result[$otherItem] = cosinSim($matrix, $item, $otherItem);
            }
        }
    }
    if ($isUser){
        foreach($numratore as $key=>$value){
            $result[$key] = $value/$denomiratore[$key];
        }
    }
    uasort($result, function($a,$b){
        if($a==$b) return 0;
        return ($a>$b) ? -1:1;
    }
    );
    return $result;
}

function get_books($rec){
    $i = 1;
    $reR = [];
    global $conn;
    foreach($rec as $book_id=>$rating){
        if($i > 6) {break;}
        $result = mysqli_query($conn, "SELECT `id`, `publisher`, `num_pages`, `image_url`, `title`
                        FROM `books` WHERE `id` = $book_id");
        while ($row = mysqli_fetch_assoc($result)){
            ///// authors ///////
            $authors = [];
            $query = mysqli_query($conn,"SELECT `name` FROM `author_book`
					JOIN `authors` ON `author_book`.`author_id` = `authors`.`id`
					WHERE `author_book`.`book_id` = ".$row["id"]);
            while ($rowQuery = mysqli_fetch_assoc($query)){
                array_push($authors,$rowQuery["name"]);
            }
            $row['authors'] =  $authors;
            ///  rating ////////////
            $query = mysqli_query($conn,"SELECT AVG(`rating`) AS `rating` FROM `reviews` WHERE `book_id` = ".$row["id"]);
            while ($rowQuery = mysqli_fetch_assoc($query)){
                $row['rate'] =  round($rowQuery['rating'],1);
            }
            // --------------------- genres
            $query = mysqli_query($conn,"SELECT * FROM `book_geners`
					JOIN `geners` ON `book_geners`.`genres_id` = `geners`.`id`
					HAVING `book_id` = ".$row["id"]);
            $genres = [];
            while ($rowQuery = mysqli_fetch_assoc($query)){
                array_push($genres,$rowQuery["name"]);
            }
            $row['genres'] =  $genres;
            array_push($reR,$row);
        } // end while ($row = mysqli_fetch_assoc($result)){
        $i++;
    } // end foreach($rec as $book_id=>$rating){
    // mysqli_close($conn);
    uasort($reR, function($a,$b){
        return $b['rate']<=>$a['rate'];
    }
    );
    return $reR;
}
