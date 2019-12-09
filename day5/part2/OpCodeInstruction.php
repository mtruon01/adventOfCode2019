<?php declare(strict_types=1);

/**
 * Created by Thong Truong (Tom)
 * Email: thong.truong@MadWireMedia.com
 * Date: 12/6/19
 * Time: 4:21 PM
 */
class OpCodeInstruction
{
    public const CODE_ADDITION = 1;

    public const CODE_MULTIPLY = 2;

    public const CODE_INPUT = 3;

    public const CODE_OUTPUT = 4;

    public const CODE_JUMP_IF_TRUE = 5;

    public const CODE_JUMP_IF_FALSE = 6;

    public const CODE_LESS_THAN = 7;

    public const CODE_EQUALS = 8;

    public const CODE_EXIT = 99;

    public const PARAMETER_MODE_POSITION = 0;

    public const PARAMETER_MODE_IMMEDIATE = 1;

    /**
     * @var int|null $instructionMode
     */
    protected $instructionMode = null;

    /**
     * @var int $paramMode1
     */
    protected $paramMode1 = 0;

    /**
     * @var int $paramMode2
     */
    protected $paramMode2 = 0;

    /**
     * @var int $paramMode3
     */
    protected $paramMode3 = 0;

    /**
     * OpCode constructor.
     * @param int $opCode
     */
    public function __construct(int $opCode)
    {
        $opCode = intval($opCode);

        $this->setInstructionMode($opCode % 100);

        if ($opCode > 99) {
            if ($opCode <= 999) {
                $this->setParamMode1(floor($opCode / 100) % 10);

            } else if ($opCode > 999) {
                $this->setParamMode1(floor($opCode / 100) % 10);
                $this->setParamMode2(floor($opCode / 1000) % 10);

            } else {
                $this->setParamMode1(floor($opCode / 100) % 10);
                $this->setParamMode2(floor($opCode / 1000) % 10);
                $this->setParamMode3(floor($opCode / 10000) % 10);
            }
        }
    }

    /**
     * @return int
     */
    public function getInstructionMode(): ?int
    {
        return $this->instructionMode;
    }

    /**
     * @param int $instructionMode
     */
    public function setInstructionMode(?int $instructionMode): void
    {
        $this->instructionMode = $instructionMode;
    }

    /**
     * @return int
     */
    public function getParamMode1(): int
    {
        return $this->paramMode1;
    }

    /**
     * @param int $paramMode1
     */
    public function setParamMode1(int $paramMode1): void
    {
        $this->paramMode1 = $paramMode1;
    }

    /**
     * @return int
     */
    public function getParamMode2(): int
    {
        return $this->paramMode2;
    }

    /**
     * @param int $paramMode2
     */
    public function setParamMode2(int $paramMode2): void
    {
        $this->paramMode2 = $paramMode2;
    }

    /**
     * @return int
     */
    public function getParamMode3(): int
    {
        return $this->paramMode3;
    }

    /**
     * @param int $paramMode3
     */
    public function setParamMode3(int $paramMode3): void
    {
        $this->paramMode3 = $paramMode3;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $param3Mode = $this->getParamMode3() * 10000;
        $param2Mode = $this->getParamMode2() * 1000;
        $param1Mode = $this->getParamMode1() * 100;

        return strval($param3Mode + $param2Mode + $param1Mode + $this->getInstructionMode());
    }


    /**
     * @param array $addresses
     * @param int $currentPosition
     * @param int $input
     */
    public function runInstruction(array &$addresses, int &$currentPosition, int $input)
    {
        switch ($this->getInstructionMode()) {
            case self::CODE_ADDITION:
            case self::CODE_MULTIPLY:
            case self::CODE_LESS_THAN:
            case self::CODE_EQUALS:
                echo "Run instruction " . $this . ", " . $addresses[$currentPosition + 1] . ", " . $addresses[$currentPosition + 2] . ", " . $addresses[$currentPosition + 3] . PHP_EOL;
                $this->runCalculationInstruction($addresses, $currentPosition);
                break;

            case self::CODE_JUMP_IF_TRUE:
            case self::CODE_JUMP_IF_FALSE:
                echo "Run instruction " . $this . ", " . $addresses[$currentPosition + 1] . ", " . $addresses[$currentPosition + 2] . PHP_EOL;
                $this->runJumpInstruction($addresses, $currentPosition);
                break;

            case self::CODE_INPUT:
                echo "Run instruction " . $this . ", " . $addresses[$currentPosition + 1] . PHP_EOL;
                $currentPosition += 1;
                $inputPosition = $addresses[$currentPosition];
                $addresses[$inputPosition] = $input;
                $currentPosition++;
                break;

            case self::CODE_OUTPUT:
                echo "Run instruction " . $this . ", " . $addresses[$currentPosition + 1] . PHP_EOL;
                $currentPosition += 1;
                $valueToOutput = $addresses[$addresses[$currentPosition]];
                echo "Output: " . $valueToOutput . PHP_EOL;
                $currentPosition++;
                break;

            case self::CODE_EXIT:
                echo "Run instruction " . $this . PHP_EOL;
                exit("Code 99 - End the program" . PHP_EOL);

            default:
                echo "Undefined OpCode Instruction: " . $this->getInstructionMode() . PHP_EOL;
                exit(1);

        }
    }

    /**
     * @param array $addresses
     * @param int $currentPosition
     */
    protected function runJumpInstruction(array &$addresses, int &$currentPosition): void
    {
        $currentPosition += 1;
        $param1 = $this->getParamValue($this->getParamMode1(), $addresses, $currentPosition);
        if ($param1 === null) {
            echo "Undefined Parameter Mode 1: " . $this->getParamMode1() . PHP_EOL;
            exit(1);
        }

        $currentPosition += 1;
        $param2 = $this->getParamValue($this->getParamMode2(), $addresses, $currentPosition);
        if ($param2 === null) {
            echo "Undefined Parameter Mode 2: " . $this->getParamMode2() . PHP_EOL;
            exit(1);
        }

        switch ($this->getInstructionMode()) {
            case self::CODE_JUMP_IF_TRUE:
                if ($param1 !== 0) {
                    $currentPosition = $param2;
                } else {
                    $currentPosition += 1;
                }
                break;

            case self::CODE_JUMP_IF_FALSE:
                if ($param1 === 0) {
                    $currentPosition = $param2;
                } else {
                    $currentPosition += 1;
                }
                break;

            default:
                echo "Undefined OpCode for Calculation: " . $this->getInstructionMode() . PHP_EOL;
                exit(1);
        }
    }

    /**
     * @param array $addresses
     * @param int $currentPosition
     */
    protected function runCalculationInstruction(array &$addresses, int &$currentPosition): void
    {
        $currentPosition += 1;
        $param1 = $this->getParamValue($this->getParamMode1(), $addresses, $currentPosition);
        if ($param1 === null) {
            echo "Undefined Parameter Mode 1: " . $this->getParamMode1() . PHP_EOL;
            exit(1);
        }

        $currentPosition += 1;
        $param2 = $this->getParamValue($this->getParamMode2(), $addresses, $currentPosition);
        if ($param2 === null) {
            echo "Undefined Parameter Mode 2: " . $this->getParamMode2() . PHP_EOL;
            exit(1);
        }

        switch ($this->getInstructionMode()) {
            case self::CODE_ADDITION:
                $result = $param1 + $param2;
                break;

            case self::CODE_MULTIPLY:
                $result = $param1 * $param2;
                break;

            case self::CODE_LESS_THAN:
                if ($param1 < $param2) {
                    $result = 1;
                } else {
                    $result = 0;
                }
                break;

            case self::CODE_EQUALS:
                if ($param1 == $param2) {
                    $result = 1;
                } else {
                    $result = 0;
                }
                break;

            default:
                echo "Undefined OpCode for Calculation: " . $this->getInstructionMode() . PHP_EOL;
                exit(1);
        }

        $currentPosition += 1;
        if ($this->getParamMode3() === self::PARAMETER_MODE_POSITION) {
            $addresses[$addresses[$currentPosition]] = $result;
        } else {
            echo "Undefined Parameter Mode 3: " . $this->getParamMode3() . PHP_EOL;
            exit(1);
        }

        $currentPosition++;
    }

    /**
     * @param int $paramMode
     * @param $addresses
     * @param $currentPosition
     *
     * @return int|null
     */
    protected function getParamValue(int $paramMode, $addresses, $currentPosition): ?int
    {
        if ($paramMode === self::PARAMETER_MODE_POSITION) {
            return $addresses[$addresses[$currentPosition]];
        } else if ($paramMode === self::PARAMETER_MODE_IMMEDIATE) {
            return $addresses[$currentPosition];
        } else {
            return null;
        }
    }
}