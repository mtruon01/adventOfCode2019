<?php declare(strict_types=1);

/**
 * Created by Thong Truong (Tom)
 * Email: thong.truong@MadWireMedia.com
 * Date: 12/6/19
 * Time: 8:45 AM
 */

require_once('OpCode.php');

class Instruction
{
    public const PARAMETER_MODE_POSITION = 0;

    public const PARAMETER_MODE_IMMEDIATE = 1;

    /**
     * @var OpCode $opCode
     */
    protected $opCode = null;

    /**
     * @var int[] $parameters
     */
    protected $parameters = [];

    /**
     * Instruction constructor.
     *
     * @param OpCode $opCode
     * @param array $parameters
     */
    public function __construct(OpCode $opCode, array $parameters = [])
    {
        $this->setOpCode($opCode);
        $this->setParameters($parameters);
    }

    /**
     * @return OpCode
     */
    public function getOpCode(): OpCode
    {
        return $this->opCode;
    }

    /**
     * @param OpCode $opCode
     */
    public function setOpCode(OpCode $opCode): void
    {
        $this->opCode = $opCode;
    }

    /**
     * @return int[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param int[] $parameters
     */
    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    /**
     * @param int[] $addresses
     */
    public function run(array &$addresses): void
    {
        $opCode = $this->getOpCode();
        $parameters = $this->getParameters();

        echo "Running instruction " . $opCode . "," . implode(",", $parameters) . PHP_EOL;

        switch ($opCode->getInstructionMode()) {
            case OpCode::CODE_ADDITION:
            case OpCode::CODE_MULTIPLY:
                $this->runCalculationInstruction($addresses);
                break;

            case OpCode::CODE_INPUT:
                $inputPosition = $parameters[0];
                $addresses[$inputPosition] = Program::getInput();
                break;

            case OpCode::CODE_OUTPUT:
                $valueToOutput = $addresses[$parameters[0]];
                echo "Output: " . $valueToOutput . PHP_EOL;
                break;

            case OpCode::CODE_EXIT:
                exit("Code 99 - End the program");

            default:
                echo "Undefined OpCode Instruction: " . $opCode->getInstructionMode() . PHP_EOL;
                exit(1);

        }
    }

    protected function runCalculationInstruction(array &$addresses): void
    {
        $opCode = $this->getOpCode();
        $parameters = $this->getParameters();

        if ($opCode->getParamMode1() === self::PARAMETER_MODE_POSITION) {
            $param1 = $addresses[$parameters[0]];
        } else if($opCode->getParamMode1() === self::PARAMETER_MODE_IMMEDIATE) {
            $param1 = $parameters[0];
        } else {
            echo "Undefined Parameter Mode 1: " . $opCode->getParamMode1() . PHP_EOL;
            exit(1);
        }

        if ($opCode->getParamMode2() === self::PARAMETER_MODE_POSITION) {
            $param2 = $addresses[$parameters[1]];
        } else if($opCode->getParamMode2() === self::PARAMETER_MODE_IMMEDIATE) {
            $param2 = $parameters[1];
        } else {
            echo "Undefined Parameter Mode 2: " . $opCode->getParamMode2() . PHP_EOL;
            exit(1);
        }

        if ($opCode->getInstructionMode() === OpCode::CODE_ADDITION) {
            $result = $param1 + $param2;
        } else {
            $result = $param1 * $param2;
        }

        if ($opCode->getParamMode3() === self::PARAMETER_MODE_POSITION) {
            $addresses[$parameters[2]] = $result;
        } else {
            echo "Undefined Parameter Mode 3: " . $opCode->getParamMode3() . PHP_EOL;
            exit(1);
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $output = $this->getOpCode()->__toString();

        if (count($this->getParameters())) {
            $output .= "," . implode(",", $this->getParameters());
        }

        return $output;
    }
}