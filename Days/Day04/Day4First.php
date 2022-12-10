<?php

namespace Days\Day04;

class Day4First implements \Days\Day
{
    public function run(array $input): int|string
    {
       return array_sum(array_map($this->overlapCondition(), $this->preprocessInput($input)));
    }

    protected function preprocessInput(array $input): array
    {
        return array_map(function ($item) {
            $parts = explode(',', $item);
            return [
                explode('-', $parts[0]),
                explode('-', $parts[1])
            ];
        }, $input);
    }

    protected function sizeOfOverlap(array $a, array $b): int
    {
        $maxStart = max($a[0], $b[0]);
        $minEnd = min($a[1], $b[1]);

        return $minEnd - $maxStart + 1;
    }

    protected function isFullOverlap(array $a, array $b): bool
    {
        $minSize = min(sizeOfInterval($a), sizeOfInterval($b));
        $overlapSize = $this->sizeOfOverlap($a, $b);

        return $minSize === $overlapSize;
    }

    protected function overlapCondition(): \Closure
    {
        return function ($pair) {
            return $this->isFullOverlap(...$pair);
        };
    }
}
