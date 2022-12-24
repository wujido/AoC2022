<?php

namespace Days\Day08;

class Day8Second extends Day8First implements \Days\Day
{

    protected function searchLine(array &$visible, $y): \Closure
    {
        return function ($tree, $x, $field) use (&$visible, $y) {
            foreach (Direction::cases() as $direction) {
                $pos = new Vector($x, $y);
                $viewScore = 0;

                do {
                    $pos = $pos->move($direction);

                    if (!$pos->isSelfInField($field)) break;

                    $viewScore++;
                } while (
                    $pos->getSelfInField($field) < $tree
                );

                $visible[$y][$x] *= $viewScore;
            }
        };
    }

    protected function accumulateArray(int &$acc): \Closure
    {
        return function ($i) use (&$acc) {
            $acc = max($i, $acc);
        };
    }

    protected function getDefaultVisible(): int
    {
        return 1;
    }
}
