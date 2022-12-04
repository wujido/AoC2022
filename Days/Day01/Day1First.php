<?php

namespace Days\Day01;

class Day1First  implements \Days\Day
{
    public function run(array $input): int|string
    {
        $max = 0;
        $elf = 0;
        foreach ($input as $item) {
            if (empty($item)) {
              $max = max($max, $elf);
              $elf = 0;
              continue;
            }

            $elf += $item;
        }

        return $max;
    }
}
