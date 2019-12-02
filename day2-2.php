<?php declare(strict_types=1);
/**
 * Created by Thong Truong (Tom)
 * Email: thong.truong@MadWireMedia.com
 * Date: 12/2/19
 * Time: 12:17 PM
 */

$originalInputs = [1,0,0,3,1,1,2,3,1,3,4,3,1,5,0,3,2,1,6,19,1,9,19,23,2,23,10,27,1,27,5,31,1,31,6,35,1,6,35,39,2,39,13,43,1,9,43,47,2,9,47,51,1,51,6,55,2,55,10,59,1,59,5,63,2,10,63,67,2,9,67,71,1,71,5,75,2,10,75,79,1,79,6,83,2,10,83,87,1,5,87,91,2,9,91,95,1,95,5,99,1,99,2,103,1,103,13,0,99,2,14,0,0];

for($input1 = 0; $input1 < 100; $input1++) {
    for($input2 = 0; $input2 < 100; $input2++) {
        $inputs = $originalInputs;
        $inputs[1] = $input1;
        $inputs[2] = $input2;
        foreach($inputs as $position => $value) {
            if($position%4 === 0) {
                switch($value) {
                    case 1:
                        $position1 = $inputs[$position+1];
                        $position2 = $inputs[$position+2];
                        $position3 = $inputs[$position+3];
                        $inputs[$position3] = $inputs[$position1] + $inputs[$position2];
                        break;

                    case 2:
                        $position1 = $inputs[$position+1];
                        $position2 = $inputs[$position+2];
                        $position3 = $inputs[$position+3];
                        $inputs[$position3] = $inputs[$position1] * $inputs[$position2];
                        break;

                    case 99:
                        break 2;

                    default:
                        echo "wrong opcode: " . $value . PHP_EOL;
                }
            }
        }

        if($inputs[0] === 19690720) {
            echo (100*$input1) + $input2;
        }
    }
}