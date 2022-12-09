<?php

namespace Days\Day02;

use http\Exception\InvalidArgumentException;

class Day2First implements \Days\Day
{
    public function run(array $input): int|string
    {
        return array_reduce($input, $this->calcRoundScore(), 0);
    }

    protected function opponentPlayed(string $input): Item
    {
        return match ($input) {
            'A' => Item::Rock,
            'B' => Item::Paper,
            'C' => Item::Scissors,
        };
    }

    protected function youPlayed(string $you, Item $opponent): Item
    {
        return match ($you) {
            'X' => Item::Rock,
            'Y' => Item::Paper,
            'Z' => Item::Scissors,
        };
    }


    protected function calcRoundScore(): \Closure
    {
        return function ($sum, $round) {
            $opponent = $this->opponentPlayed($round[0]);
            $you = $this->youPlayed($round[2], $opponent);

            $itemScore = $this->itemScore($you);
            $outcomeScore = $this->outcomeScore($you, $opponent);

            return $sum + $itemScore + $outcomeScore;
        };
    }

    protected function itemScore(Item $item): int
    {
       return match ($item) {
           Item::Rock => 1,
           Item::Paper => 2,
           Item::Scissors => 3,
       };
    }

    protected function outcomeScore(Item $you, Item $opponent): int
    {
        if ($you === $opponent)
            return 3;

        if ($this->whatPlayToWin($opponent) === $you)
            return 6;

        return 0;
    }


    protected function whatPlayToWin(Item $item): Item
    {
       return match ($item)
       {
           Item::Rock => Item::Paper,
           Item::Paper => Item::Scissors,
           Item::Scissors => Item::Rock,
       };
    }
}
