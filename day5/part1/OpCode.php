<?php declare(strict_types=1);

/**
 * Created by Thong Truong (Tom)
 * Email: thong.truong@MadWireMedia.com
 * Date: 12/6/19
 * Time: 8:37 AM
 */
class OpCode
{
    public const CODE_ADDITION = 1;

    public const CODE_MULTIPLY = 2;

    public const CODE_INPUT = 3;

    public const CODE_OUTPUT = 4;

    public const CODE_EXIT = 99;

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

        $this->setInstructionMode($opCode%100);

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
}