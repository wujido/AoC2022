<?php

namespace Days\Day13;

class Day13First  implements \Days\Day
{
    public function run(array $input): int|string
    {
        $packets = $this->parsePackets($input);
        $pairs = array_chunk($packets, 2);

        $res = 0;
        foreach ($pairs as $i => $pair) {
            if ($this->isInRightOrder($pair))
                $res += $i + 1;
        }

        return $res;
    }

    protected function isInRightOrder(array $pair)
    {
        list($leftList, $rightList) = $pair;

        while (!empty($leftList) && !empty($rightList)) {
            $left = array_shift($leftList);
            $right = array_shift($rightList);

            if (is_array($left) && is_int($right))
                $right = [$right];

            if (is_int($left) && is_array($right))
                $left = [$left];

            if (is_int($left) && is_int($right)) {
                if ($left < $right) return true;
                if ($left > $right) return false;
                continue;
            }

            if (is_array($left) && is_array($right)) {
                $res = $this->isInRightOrder([$left, $right]);
                if (!is_null($res)) return $res;
            }
        }

        if (empty($leftList) && empty($rightList))
            return null;

        return empty($leftList);
    }

    /**
     * @param array $input
     * @return array
     */
    public function parsePackets(array $input): array
    {
        $packets = array_map(fn($p) => eval("return $p;"), $input);
        $packets = array_filter($packets, fn($a) => !is_null($a));
        return $packets;
    }
}
