<?php

namespace Days\Day05;

class Day5Second extends Day5First implements \Days\Day
{
    protected function cranePut(array &$crane)
    {
        return array_pop($crane);
    }
}
