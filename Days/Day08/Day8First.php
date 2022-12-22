<?php

namespace Days\Day08;

class Day8First implements \Days\Day
{
    public function run(array $input): int|string
    {
        $field = array_map(fn($i) => mb_str_split($i), $input);

        $visible = $this->initVisible($field);
        $visible = $this->searchField($field, $visible);

        return $this->countVisible($visible);
    }

    private function searchLine($visible, $line)
    {
        $highestTree = -1;
        array_walk($line, function ($tree, $i) use (&$visible, &$highestTree) {
            if ($tree > $highestTree)
                $visible[strval($i)] = $highestTree = $tree;
        });
        return $visible;
    }

    public function searchLines(array $lines, array $visible): array
    {
        array_walk($lines, function ($line, $i) use (&$visible) {
            $visible[strval($i)] = $this->searchLine($visible[$i], $line);
            $visible[strval($i)] = array_reverse($this->searchLine(array_reverse($visible[$i]), array_reverse($line)));
        });
        return array($lines, $visible);
    }

    public function initVisible(array $lines): array
    {
        return array_fill(0, count($lines), array_fill(0, count($lines[0]), null));
    }

    public function searchField(array $lines, array $visible): array
    {
        list($lines, $visible) = $this->searchLines($lines, $visible);
        list($lines, $visible) = $this->searchLines(transpose($lines), transpose($visible));
        return $visible;
    }

    public function countVisible(array $visible): int
    {
        $visibleCount = 0;
        array_walk_recursive($visible, function ($t) use (&$visibleCount) {
            if (!is_null($t)) $visibleCount++;
        });

        return $visibleCount;
    }
}
