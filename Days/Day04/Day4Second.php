<?php

namespace Days\Day04;

class Day4Second extends Day4First implements \Days\Day
{
    protected function overlapCondition(): \Closure
    {
        return function ($pair) {
            return $this->sizeOfOverlap(...$pair) > 0;
        };
    }
}
