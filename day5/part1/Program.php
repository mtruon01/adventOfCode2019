<?php declare(strict_types=1);

/**
 * Created by Thong Truong (Tom)
 * Email: thong.truong@MadWireMedia.com
 * Date: 12/6/19
 * Time: 8:50 AM
 */

require_once('OpCode.php');
require_once('Instruction.php');

class Program
{
    /**
     * @var int $input
     */
    protected static $input;

    /**
     * @var int $output
     */
    protected $output;

    /**
     * @var string $instructions
     */
    protected $instructions;

    /**
     * Program constructor.
     *
     * @param string $instructions
     */
    public function __construct(string $instructions)
    {
        $this->setInstructions($instructions);
    }

    /**
     * @return int
     */
    public static function getInput(): int
    {
        return self::$input;
    }

    /**
     * @param int $input
     */
    public static function setInput(int $input): void
    {
        self::$input = $input;
    }

    /**
     * @return int
     */
    public function getOutput(): int
    {
        return $this->output;
    }

    /**
     * @param int $output
     */
    public function setOutput(int $output): void
    {
        $this->output = $output;
    }

    /**
     * @return string
     */
    public function getInstructions(): string
    {
        return $this->instructions;
    }

    /**
     * @param string $instructions
     */
    public function setInstructions(string $instructions): void
    {
        $this->instructions = $instructions;
    }

    /**
     * Take a string of instructions and parse them into an array of Instruction
     */
    public function runInstructions(): void
    {
        $instructions = $this->getInstructions();
        $addressArray = explode(",", $instructions);

        for ($i = 0; $i < count($addressArray); $i++) {
            $opCode = new OpCode(intval($addressArray[$i]));

            switch ($opCode->getInstructionMode()) {
                case OpCode::CODE_ADDITION:
                case OpCode::CODE_MULTIPLY:
                    $parameters = [
                        $addressArray[$i + 1],
                        $addressArray[$i + 2],
                        $addressArray[$i + 3]
                    ];
                    $i += 3;
                    break;

                case OpCode::CODE_INPUT:
                case OpCode::CODE_OUTPUT:
                    $parameters = [
                        $addressArray[$i + 1]
                    ];
                    $i += 1;
                    break;

                case OpCode::CODE_EXIT:
                    $parameters = [];
                    break;

                default:
                    echo "Undefined OpCode Instruction: " . $opCode->getInstructionMode() . PHP_EOL;
                    exit(1);
            }

            $instruction = new Instruction($opCode, $parameters);
            $instruction->run($addressArray);
        }
    }
}