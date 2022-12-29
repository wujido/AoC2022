<?php

namespace Days\Day12;

use Days\Day08\Direction;
use Days\Day08\Vector;
use Ds\Queue;

class Day12First implements \Days\Day
{
    public function run(array $input): int|string
    {
        $field = $this->parseField($input);

        $start = $this->getAndMaskItem($field, 'S', 'a');
        $end = $this->getAndMaskItem($field, 'E', 'z');

        return $this->BFS($start, $field, $end);
    }

    protected function findLetter(array $field, string $letter): Vector
    {
        foreach ($field as $y => $row) {
            foreach ($row as $x => $item) {
                if ($item === $letter)
                    return new Vector($x, $y);
            }

        }

        throw new \InvalidArgumentException('Field does not contain start symbol (S)');
    }

    protected function canClimb($from, $to): bool
    {
        $heightDiff = ord($to) - ord($from);
        return $heightDiff <= 1;
    }

    protected function BFS(Vector $start, array $field, Vector $end): string
    {
        $queue = new Queue([[$start, 0, [$start]]]);
        $visited = ["$start" => 1];
//        $res = "";
        do {
            list($pos, $steps, $history) = $queue->pop();

            foreach (Direction::cases() as $direction) {
                $newPos = $pos->move($direction);

                if (!$newPos->isSelfInField($field) || isset($visited["$newPos"]))
                    continue;

                $from = $pos->getSelfInField($field);
                $to = $newPos->getSelfInField($field);


                if ($this->canClimb($from, $to)) {
                    if ("$newPos" === "$end") {
                        $steps++;
                        $historyLetters = array_map(function (Vector $p) use ($field) {
                           return $p->getSelfInField($field);
                        }, $history);
                        return $steps;
//                        return $res . "END: $steps";
                    }

                    $visited["$newPos"] = 1;
                    $queue->push([$newPos, $steps + 1, [...$history, $newPos]]);
                }

//                $res .= "$pos ($from) => $newPos ($to)</br>";
            }
        } while (!$queue->isEmpty());

        return PHP_INT_MAX;
//        return $res . "Queue is empty";
    }

    protected function parseField(array $input): array
    {
        $input = array_map(fn($i) => str_replace(['<em>', '</em>'], '', $i), $input);
        return array_map('str_split', $input);
    }

    protected function getAndMaskItem(array &$field, $find, $mask): Vector
    {
        $start = $this->findLetter($field, $find);
        $start->setSelfInField($field, $mask);
        return $start;
    }
}
