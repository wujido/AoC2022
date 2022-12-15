<?php

namespace Days\Day07;

use Util\ContentLoader;

class Day7First implements \Days\Day
{
    function __construct()
    {
        ContentLoader::$TEST_CODE_BLOCK_NUMBER = 1;
    }

    public function run(array $input): int|string
    {
        $fileSystem = array_reduce($input, function (FileSystem $fileSystem, $line) {
            return $this->isCommand($line)
                ? $this->processCommand($line, $fileSystem)
                : $this->processLsOutput($line, $fileSystem);
        }, new FileSystem());

        return $this->calculateSize($fileSystem);
    }

    protected function isCommand(string $line): bool
    {
        return $line[0] === '$';
    }

    protected function parseCommand(string $cmd): array
    {
        $parts = explode(' ', $cmd);
        array_shift($parts);
        return [
            'name' => array_shift($parts),
            'args' => $parts,
        ];
    }

    protected function processCommand($line, FileSystem $fileSystem): FileSystem
    {
        $cmd = $this->parseCommand($line);

        if ($cmd['name'] === 'cd')
            $fileSystem->changeDirectory($cmd['args'][0]);

        return $fileSystem;
    }

    protected function processLsOutput($line, FileSystem $fileSystem): FileSystem
    {
        $parts = explode(' ', $line);
        if ($parts[0] === 'dir')
            $fileSystem->createDirectory($parts[1]);
        else
            $fileSystem->getCurrentDir()->addFile(intval($parts[0]));

        return $fileSystem;
    }

    protected function calculateSize(FileSystem $fileSystem): mixed
    {
        return reduce(
            $fileSystem,
            fn($sum, $dir) => $sum + ($dir <= 100000 ? $dir : 0),
            0
        );
    }
}


