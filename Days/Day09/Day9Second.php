<?php

namespace Days\Day09;

use Days\Day08\Vector;
use Util\ContentLoader;

class Day9Second extends Day9First implements \Days\Day
{
    protected function generateKnots(): array
    {
        return array_fill(0, 10, new Vector(0, 0));
    }
}
