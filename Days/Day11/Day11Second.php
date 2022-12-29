<?php

namespace Days\Day11;

class Day11Second extends Day11First implements \Days\Day
{
    protected int $ROUNDS = 10000;
    protected bool $DEBUG_INSPECTED = false;
    private int $lcd;

    protected function preprocessMonkeys(array $monkeys): array
    {
        $divisors = array_map(fn ($m) => $m->getDivisible(), $monkeys);
        $this->lcd = array_reduce($divisors, fn($lcd, $d) => $lcd * $d, 1);

        return $monkeys;
    }

    protected function modifyItem(int $item, int $divisible): int
    {
        return $item % $this->lcd;
    }
}
