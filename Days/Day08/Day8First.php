<?php

namespace Days\Day08;

use Util\ExecutionTime;

class Day8First implements \Days\Day
{
    public function run(array $input): int|string
    {
        $field = $this->splitInputToField($input);

        $visible = $this->initVisible($field);
        array_walk($field, $this->searchField($visible), $field);

        $count = 0;
        array_walk_recursive($visible, $this->accumulateArray($count));

        return $count;
    }

    protected function splitInputToField(array $input): array
    {
        return array_map(fn($i) => mb_str_split($i), $input);
    }

    protected function searchField(array &$visible): \Closure
    {
        return function ($line, $y, $field) use (&$visible) {
            array_walk($line, $this->searchLine($visible, $y), $field);
        };
    }

    protected function searchLine(array &$visible, $y): \Closure
    {
        return function ($tree, $x, $field) use (&$visible, $y) {
            foreach (Direction::cases() as $direction) {
                $pos = new Vector($x, $y);

                do {
                    $pos = $pos->move($direction);
                } while (
                    $pos->isSelfInField($field) &&
                    $pos->getSelfInField($field) < $tree
                );

                if (!$pos->isSelfInField($field)) {
                    $visible[$y][$x] = $tree;
                    break;
                }
            }
        };
    }

    protected function accumulateArray(int &$acc): \Closure
    {
        return function ($i) use (&$acc) {
            $acc += is_null($i) ? 0 : 1;
        };
    }

    protected function initVisible(array $field): array
    {
        return array_fill(0, count($field), array_fill(0, count($field[0]), $this->getDefaultVisible()));
    }

    protected function getDefaultVisible(): ?int
    {
        return null;
    }
}
