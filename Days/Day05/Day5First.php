<?php

namespace Days\Day05;

class Day5First implements \Days\Day
{
    public function run(array $input): int|string
    {
        list($stacks, $commands) = $this->preprocessInput($input);
        $stacks = array_reduce($commands, $this->applyCommandOnStacks(), $stacks);
        return array_reduce($stacks, fn($msg, $stack) => $msg . $stack[array_key_last($stack)], '');
    }

    protected function parseInput(array $input): mixed
    {
        return array_reduce($input, function ($res, $line) {
            if (!empty($line) && $line[0] === 'm') {
                $res[1][] = $this->parseCommand($line);
            } else {
                $res[0][] = everyNthChar($line, 4, 1);
            }

            return $res;
        }, [[], []]);
    }

    protected function preprocessInput(array $input): array
    {
        list($stacks, $commands) = $this->parseInput($input);
        array_pop($stacks);
        array_pop($stacks);
        $stacks = transpose($stacks);
        $stacks = $this->filterEmptyItems($stacks);
        $stacks = array_map('array_reverse', $stacks);

        return [$stacks, $commands];
    }


    protected function filterEmptyItems(array $stacks): array
    {
        return array_map(function ($stack) {
            return array_filter($stack, function ($item) {
                return (trim($item) != "");
            });
        }, $stacks);
    }

    protected function parseCommand(string $command): array
    {
        preg_match("/move (\d*) from (\d*) to (\d*)/", $command, $matches);
        array_shift($matches);
        return [
            intval($matches[0]),
            intval($matches[1]) - 1,
            intval($matches[2]) - 1
        ];
    }

    protected function applyCommandOnStacks(): \Closure
    {
        return function ($stacks, $command) {
            list($count, $from, $to) = $command;

            $crane = [];
            for ($i = 0; $i < $count; $i++)
                $this->cranePick($crane, array_pop($stacks[$from]));

            for ($i = 0; $i < $count; $i++)
                $stacks[$to][] = $this->cranePut($crane);

            return $stacks;
        };
    }

    protected function cranePick(array &$crane, string $item)
    {
        $crane[] = $item;
    }

    protected function cranePut(array &$crane)
    {
        return array_shift($crane);
    }

}
