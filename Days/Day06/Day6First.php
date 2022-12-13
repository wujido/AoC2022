<?php

namespace Days\Day06;

class Day6First implements \Days\Day
{
    protected int $searchCount = 4;
    public function run(array $input): int|string
    {
        $read = [];
        $pos = 0;
        while (count($read) < $this->searchCount) {
            $ch = $input[$pos];

            if (!isset($read[$ch])) {
                $pos++;
                $read[$ch] = $pos;
            } else {
                $pos = $read[$ch];
                $read = [];
            }
        }
        return $pos;
    }
}
