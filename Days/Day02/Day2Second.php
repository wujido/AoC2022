<?php

namespace Days\Day02;

class Day2Second extends Day2First implements \Days\Day
{
    protected function youPlayed(string $you, Item $opponent): Item
    {
        return match ($you) {
            'X' => $this->whatPlayToWin($this->whatPlayToWin($opponent)),
            'Y' => $opponent,
            'Z' => $this->whatPlayToWin($opponent),
        };
    }
}
