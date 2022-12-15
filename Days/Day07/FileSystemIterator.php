<?php

namespace Days\Day07;

class FileSystemIterator implements \Iterator
{
    private Directory $root;
    private ?Directory $context;
    private \SplStack $stack;


    public function __construct(Directory $root)
    {
        $this->root = $this->context = $root;
        $this->stack = new \SplStack();
    }

    public function current(): int
    {
        return $this->context->getSize();
    }

    public function next(): void
    {
        if ($this->stack->isEmpty()) {
            $this->context = null;
            return;
        }

        $cur = $this->stack->pop();
        $item = array_shift($cur);
        $subDirs = $this->getIterableDirs($item->getSubDirs());

        if (!empty($cur))
            $this->stack->push($cur);

        if (!empty($subDirs))
            $this->stack->push($subDirs);

        $this->context = $item;
    }

    public function key(): string
    {
        return $this->context->getName();
    }

    public function valid(): bool
    {
        return !is_null($this->context);
    }

    public function rewind(): void
    {
        $this->context = $this->root;
        $this->stack->push($this->getIterableDirs($this->root->getSubDirs()));
    }

    private function getIterableDirs(array $dirs): array
    {
        return array_filter(
            $dirs,
            fn($dir, $name) => !is_null($dir) && $name != '..',
            ARRAY_FILTER_USE_BOTH
        );
    }
}