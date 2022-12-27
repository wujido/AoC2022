<?php

namespace Days\Day10;

class Instruction
{
    private string $name;
    private int $arg;

    private function __construct($name, $arg = 0)
    {

        $this->name = $name;
        $this->arg = $arg;
    }

    public static function fromText(string $instruction): Instruction
    {
        list($instruction, $argument) = explode(' ', $instruction);
        return match ($instruction) {
            'noop' => new Instruction($instruction),
            'addx' => new Instruction($instruction, $argument)
        };
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function execute(CPU $cpu): void
    {
        $cpu->{$this->name}($this->arg);
    }
}