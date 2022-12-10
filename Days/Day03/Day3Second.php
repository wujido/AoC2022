<?php

namespace Days\Day03;

class Day3Second extends Day3First implements \Days\Day
{
    protected function preprocessInput(array $input): array
    {
        return array_chunk(parent::preprocessInput($input), 3);
    }

    public function getCompartments(array $rucksack): array
    {
        return $rucksack;
    }
}
