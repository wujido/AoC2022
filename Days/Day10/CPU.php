<?php

namespace Days\Day10;

class CPU
{
    private array $registers = ['X' => 1];
    private int $cycle = 0;
    private $inspect;

    public function __construct(callable $inspect = null)
    {
        $this->inspect = is_null($inspect)
            ? function () {
            }
            : $inspect;
    }

    public function getCycle(): int
    {
        return $this->cycle;
    }

    public function getRegister(string $reg)
    {
        return $this->registers[$reg];
    }

    public function tick(): void
    {
        $this->cycle++;
    }

    public function addx($add): void
    {
        for ($i = 0; $i < 2; $i++) {
            $this->tick();
            $this->callInspect();
        }

        $this->registers['X'] += $add;
    }

    public function noop(): void
    {
        $this->tick();
        $this->callInspect();
    }

    public function callInspect(): void
    {
        ($this->inspect)($this);
    }
}