<?php

namespace Days\Day07;

class Directory
{
    private int $size = 0;
    private string $name;
    private array $subDirs;

    public function __construct(string $name, ?Directory $parent)
    {
        $this->name = $name;
        $this->subDirs['..'] = $parent;
    }

    public function addFile(int $size): void
    {
       $this->size += $size;
       $this->subDirs['..']?->addFile($size);
    }

    public function addDir(Directory $dir): void
    {
        $this->subDirs[$dir->getName()] = $dir;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSubDirs(): array
    {
        return $this->subDirs;
    }
}