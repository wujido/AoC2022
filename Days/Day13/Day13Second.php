<?php

namespace Days\Day13;

class Day13Second extends Day13First implements \Days\Day
{
    public function run(array $input): int|string
    {
        $packets = $this->parsePackets($input);
        $packets = [...$packets, [[2]], [[6]]];
        usort($packets, fn($a, $b) => $this->isInRightOrder([$a, $b]));
        $packets = array_reverse($packets);

        $first = array_search([[2]], $packets) + 1;
        $second = array_search([[6]], $packets) + 1;

        return $first * $second;
    }
}
