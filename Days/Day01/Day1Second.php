<?php

namespace Days\Day01;

class Day1Second extends Day1First implements \Days\Day
{
    public function run(array $input): int|string
    {
        $max = [0, 0, 0];
        $elf = 0;
        foreach ($input as $item) {
            if (empty($item)) {
                $min = array_search(min($max), $max);
                $max[$min] = max($max[$min], $elf);

                $elf = 0;
                continue;
            }

            $elf += $item;
        }

        return array_sum($max);
    }
}
