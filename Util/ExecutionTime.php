<?php

namespace Util;

class ExecutionTime
{
    public static int $iterations = 1;
    private array|false $startTime;
    private array|false $endTime;
    private int|float $cpu = 0;
    private int|float $syscall = 0;

    private function start(): void
    {
        $this->startTime = getrusage();
    }

    private function end(): void
    {
        $this->endTime = getrusage();
    }

    private function runTime($index): float|int
    {
        return ($this->endTime["ru_$index.tv_sec"] * 1000 + intval($this->endTime["ru_$index.tv_usec"] / 1000))
            - ($this->startTime["ru_$index.tv_sec"] * 1000 + intval($this->startTime["ru_$index.tv_usec"] / 1000));
    }

    public function __toString()
    {
        return "CPU:&nbsp;$this->cpu&nbsp;ms</br>
                Syscall:&nbsp;$this->syscall&nbsp;ms";
    }

    public static function measure(callable $func, ...$args): array
    {
        $executionTime = new ExecutionTime();

        for ($i = 0; $i < ExecutionTime::$iterations; $i++) {
            $executionTime->start();
            $result = call_user_func($func, ...$args);
            $executionTime->end();

            $executionTime->cpu += $executionTime->runTime("utime") / ExecutionTime::$iterations;
            $executionTime->syscall += $executionTime->runTime("stime") / ExecutionTime::$iterations;
        }

        return [
            'result' => $result,
            'execTime' => "$executionTime"
        ];
    }
}
