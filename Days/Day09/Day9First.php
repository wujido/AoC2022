<?php

namespace Days\Day09;

use Days\Day08\Direction;
use Days\Day08\Vector;
use Util\ContentLoader;

class Day9First implements \Days\Day
{
    function __construct()
    {
        ContentLoader::$TEST_CODE_BLOCK_NUMBER = 7;
    }

    public function run(array $input): int|string
    {
        $commands = array_map(fn($str) => explode(' ', $str), $input);
        $data = array_reduce($commands, $this->applyCommand(), [
            $this->generateKnots(),
            ["(0, 0)" => 1]
        ]);

        return count($data[1]);
    }

    private function getDirection(string $str): Direction
    {
        return match ($str) {
            'L' => Direction::Left,
            'R' => Direction::Right,
            'U' => Direction::Up,
            'D' => Direction::Down,
        };
    }

    protected function generateKnots(): array
    {
        return array_fill(0, 2, new Vector(0, 0));
    }

    public function applyCommand(): \Closure
    {
        return function ($data, $command) {
            list($direction, $steps) = $command;
            list($knots, $visited) = $data;
            $direction = $this->getDirection($direction);

            for ($i = 0; $i < $steps; $i++) {
                $head = $knots[0] = $knots[0]->move($direction);
                $knots = array_map($this->moveTail($head), $knots);

                $last = $knots[array_key_last($knots)];
                $visited["$last"] = 1;
            }

            return [$knots, $visited];
        };
    }

    function moveTail($head): \Closure
    {
        return function ($knot) use (&$head) {
            if ($head->distance($knot) >= 2) {
                $knot = $knot->add($knot->gridDirection($head));
            }
            $head = $knot;
            return $knot;
        };
    }
}
