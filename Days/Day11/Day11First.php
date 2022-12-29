<?php

namespace Days\Day11;

use Closure;

class Day11First implements \Days\Day
{
    protected int $ROUNDS = 20;
    protected bool $DEBUG_ITEMS = false;
    protected bool $DEBUG_INSPECTED = false;

    public function run(array $input): int|string
    {
        $monkeys = array_chunk($input, 7);
        $monkeys = array_map(['Days\Day11\Monkey', 'fromArray'], $monkeys);

        $itemProcessor = $this->getItemProcessor($monkeys);
        $monkeys = $this->preprocessMonkeys($monkeys);

        for ($i = 0; $i < $this->ROUNDS; $i++) {
            foreach ($monkeys as $monkey) {
                $monkey->simulateRound($itemProcessor);
            }

            if ($this->DEBUG_ITEMS)
                $this->printMonkeysItems($i + 1, $monkeys);

            if ($this->DEBUG_INSPECTED && in_array($i, [0, 19, 999, 1999, 2999, 3999, 4999, 5999, 6999, 7999, 8999, 9999])) {
                $this->printInspected($i + 1, $monkeys);
            }
        }

        $inspected = array_map(fn($m) => $m->getInspected(), $monkeys);
        sort($inspected);
        $inspected = array_reverse($inspected);

        return $inspected[0] * $inspected[1];
    }

    protected function getItemProcessor(&$monkeys): Closure
    {
        return function (int $item, Closure $operation, int $divisible, int $true, int $false) use (&$monkeys) {
            $item = $operation($item);
            $item = $this->modifyItem($item, $divisible);

            $monkey = $item % $divisible === 0
                ? $true
                : $false;

            $monkeys[$monkey]->addItem($item);
        };
    }

    protected function modifyItem(int $item, int $divisible): int
    {
        return intdiv($item, 3);
    }

    protected function printMonkeysItems($round, $monkeys)
    {
        printf("After round %d, the monkeys are holding items with these worry levels:\n", $round);
        foreach ($monkeys as $i => $monkey) {
            printf("Monkey %d: %s\n", $i, $monkey->Items());
        }
        printf("\n");
    }

    protected function printInspected($round, $monkeys)
    {
        printf("== After round %d ==\n", $round);
        $inspected = array_map(fn($m) => $m->getInspected(), $monkeys);
        foreach ($inspected as $i => $ins) {
            printf("Monkey %d inspected items %d times.\n", $i, $ins);
        }
        printf("\n");
    }

    protected function preprocessMonkeys(array $monkeys): array
    {
        return $monkeys;
    }
}
