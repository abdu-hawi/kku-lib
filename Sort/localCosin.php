<?php

$matrix = [
    "u1" => [
        "b1"=>5, "b2"=>0, "b3"=>0, "b4"=>1, "b5"=>4, "b6"=>0, "b7"=>0
    ],
    "u2" => [
        "b1"=>0, "b2"=>0, "b3"=>5, "b4"=>0, "b5"=>1, "b6"=>4, "b7"=>3
    ],
    "u3" => [
        "b1"=>4, "b2"=>5, "b3"=>0, "b4"=>2, "b5"=>5, "b6"=>0, "b7"=>1
    ],
    "u4" => [
        "b1"=>2, "b2"=>0, "b3"=>4, "b4"=>0, "b5"=>1, "b6"=>5, "b7"=>5
    ],
    "u5" => [
        "b1"=>0, "b2"=>3, "b3"=>1, "b4"=>5, "b5"=>0, "b6"=>0, "b7"=>0
    ],
];
?>

<table border="1" style="text-align: center">
    <thead>
    <tr>
        <td width="70"></td>
        <td width="70">Book1</td>
        <td width="70">Book2</td>
        <td width="70">Book3</td>
        <td width="70">Book4</td>
        <td width="70">Book5</td>
        <td width="70">Book6</td>
        <td width="70">Book7</td>
    </tr>
    </thead>
    <tbody>
<?php
foreach ($matrix as $key=>$value){
    echo "<tr>";
    echo "<td>$key</td>";
    foreach ($value as $item){
        echo "<td>$item</td>";
    }
    echo "</tr>";
}
?>
    </tbody>
</table>

<?php

echo "<pre>";
print_r(recommindetion($matrix,"u1"));
echo "</pre>";

function recommindetion($matrix,$item){
    $numratore = []; // ["b1" => 0, "b2" =>0 ,.....bn =>0]
    $denomiratore = [];
    foreach ($matrix as $otherItem=>$itemValue){
        if ($otherItem != $item){
            $sim = cosinSim($matrix, $item, $otherItem);
            foreach($matrix[$otherItem] as $book=>$rating){
                if ($matrix[$item][$book] == 0){
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
        }
    }

    foreach($numratore as $key=>$value){
        $result[$key] = $value/$denomiratore[$key];
    }
    uasort($result, function($a,$b){
        if($a==$b) return 0;
        return ($a>$b) ? -1:1;
    }
    );
    return $result;

}

function cosinSim($matrix, $item, $otherItem){
    $numeatore = 0;
    $denItem = 0;
    $denOtherItem = 0;
    foreach ($matrix[$item] as $key=>$value){
        $numeatore += $value * $matrix[$otherItem][$key];
        $denItem += pow($value,2); // u1
        $denOtherItem += pow($matrix[$otherItem][$key],2); // other item
    }
    return $numeatore / ( sqrt($denItem) * sqrt($denOtherItem) );
}
