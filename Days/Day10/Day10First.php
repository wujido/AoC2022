<?php

namespace Days\Day10;

use Util\ContentLoader;

class Day10First implements \Days\Day
{
    function __construct()
    {
        ContentLoader::$TEST_CODE_BLOCK_NUMBER = 1;
    }

    public function run(array $input): int|string
    {
        $instructions = array_map(['Days\Day10\Instruction', 'fromText'], $input);

        $data = [];
        $cpu = new CPU($this->cupInspect($data));

        foreach ($instructions as $instruction) {
            $instruction->execute($cpu);
        }

        return $this->calcResult($data);
    }

    protected function cupInspect(array &$data): \Closure
    {
        return function (CPU $cpu) use (&$data) {
            if (($cpu->getCycle() - 20) % 40 === 0) {
                $data[] = $cpu->getRegister('X') * $cpu->getCycle();
            }
        };
    }

    public function calcResult(array $data): int|float|string
    {
        return array_sum($data);
    }
}
