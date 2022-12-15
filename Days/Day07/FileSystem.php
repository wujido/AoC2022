<?php

namespace Days\Day07;

use Traversable;

class FileSystem implements \IteratorAggregate
{
    private Directory $root;
    private Directory $context;

    public function __construct()
    {
        $root = new Directory('/', null);
        $this->root = $this->context = $root;
    }

    public function createDirectory(string $name): void
    {
        $this->context->addDir(new Directory($name, $this->context));
    }

    public function changeDirectory(string $name): void
    {
        $this->context = $name === '/'
            ? $this->root
            : $this->context->getSubDirs()[$name];
    }

    public function getCurrentDir(): Directory
    {
        return $this->context;
    }

    public function getRootDir(): Directory
    {
        return $this->root;
    }

    public function getIterator(): Traversable
    {
        return new FileSystemIterator($this->root);
    }
}

