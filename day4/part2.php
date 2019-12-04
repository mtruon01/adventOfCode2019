<?php declare(strict_types=1);
/**
 * Created by Thong Truong (Tom)
 * Email: thong.truong@MadWireMedia.com
 * Date: 12/4/19
 * Time: 8:02 AM
 */

$min = 272091;
$max = 815432;

echo getNumOfPossibilities($min, $max);

function getNumOfPossibilities($min, $max): int
{
    $numOfPossibilities = 0;

    for ($i = $min; $i <= $max; $i++) {
        $array = array_map('intval', str_split(strval($i)));
        $numOfAdjacent = 0;
        $adjacentSame = false;

        for ($j = 0; $j < count($array) - 1; $j++) {
            if ($array[$j] > $array[$j + 1]) {
                continue 2;
            } else if ($array[$j] === $array[$j + 1]) {
                $numOfAdjacent++;
            } else {
                if ($numOfAdjacent === 1) {
                    $adjacentSame = true;
                }
                $numOfAdjacent = 0;
            }
        }

        if ($adjacentSame === true || $numOfAdjacent === 1) {
            echo $i . PHP_EOL;
            $numOfPossibilities++;
        }
    }

    return $numOfPossibilities;
}