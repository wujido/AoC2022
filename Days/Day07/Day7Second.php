<?php

namespace Days\Day07;

class Day7Second extends Day7First implements \Days\Day
{
    protected function calculateSize(FileSystem $fileSystem): mixed
    {
        $rootSize = $fileSystem->getRootDir()->getSize();
        $requiredSpace = $rootSize - 40000000;
        return reduce(
            $fileSystem,
            function ($minToDelete, $dir) use ($requiredSpace) {
                return $dir >= $requiredSpace
                    ? min($minToDelete, $dir)
                    : $minToDelete;
            },
            $rootSize
        );
    }
}
