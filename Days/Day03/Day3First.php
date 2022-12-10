<?php

namespace Days\Day03;

class Day3First implements \Days\Day
{
    public function run(array $input): int|string
    {
        return array_reduce($this->preprocessInput($input), $this->sumPriorities(), 0);
    }

    protected function preprocessInput(array $input): array
    {
        return array_map(function ($rucksack) {
            return mb_str_split($rucksack);
        }, $input);
    }

    protected function sumPriorities(): \Closure
    {
        return function ($total, $rucksack) {
            $compartments = $this->getCompartments($rucksack);

            $intersection = $this->toPriorityArray(array_shift($compartments));
            foreach ($compartments as $compartment) {
                $priorityArray = $this->toPriorityArray($compartment);

                foreach ($intersection as $item) {
                    if (!isset($priorityArray[$item])) {
                        unset($intersection[$item]);
                    }
                }

            }

            $priority = array_sum($intersection);
            return $total + $priority;
        };
    }

    protected function getPriority(string $char): int
    {
        $char = mb_ord($char);
        if (($char > 64 && $char < 91)) {
            return $char - 38;
        } elseif ($char > 96 && $char < 123) {
            return $char - 96;
        }

        return 0;
    }

    protected function toPriorityArray($arr): array
    {
        return array_reduce($arr, function ($arr, $ch) {
            $priority = $this->getPriority($ch);
            $arr[$priority] = $priority;
            return $arr;
        }, []);
    }

    function getCompartments(array $rucksack): array
    {
        return array_chunk($rucksack, count($rucksack) / 2);
    }
}
