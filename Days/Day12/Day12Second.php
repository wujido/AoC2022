<?php

namespace Days\Day12;

use Days\Day08\Vector;

class Day12Second extends Day12First implements \Days\Day
{
    public function run(array $input): int|string
    {
        $field = $this->parseField($input);

        $start = $this->getAndMaskItem($field, 'S', 'a');
        $end = $this->getAndMaskItem($field, 'E', 'z');
        $starts = $this->findAllLetters($field, 'a');

        return array_reduce($starts, function ($min, $start) use ($field, $end) {
            $length = $this->BFS($start, $field, $end);
            return min($min, $length);
        }, PHP_INT_MAX);
    }

    private function findAllLetters($field, $letter): array
    {
        $letters = [];
        foreach ($field as $y => $row) {
            foreach ($row as $x => $item) {
                if ($letter === $item)
                    $letters[] = new Vector($x, $y);
            }
        }
        return $letters;
    }
}
