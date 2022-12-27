<?php

namespace Days\Day10;

class Day10Second extends Day10First implements \Days\Day
{
    protected function cupInspect(array &$data): \Closure
    {
        return function (CPU $cpu) use (&$data) {
            $cycle = $cpu->getCycle() - 1;
            $position = $cycle % 40;
            $x = $cpu->getRegister('X');

            $data[] = ($position - 1 <= $x && $x <= $position + 1)
                ? '#'
                : '&nbsp;';
        };
    }

    public function calcResult(array $data): int|float|string
    {
        $rows = array_chunk($data, 40);
        $rows = array_map(fn($row) => [...$row, '</br>'], $rows);
        $rows = array_map('implode', $rows);
        return implode($rows);
    }
}
