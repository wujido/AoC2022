<?php
namespace Util;

class ExecutionTime
{
    private array|false $startTime;
    private array|false $endTime;

    public function start(): void
    {
        $this->startTime = getrusage();
    }

    public function end(): void
    {
        $this->endTime = getrusage();
    }

    private function runTime($ru, $rus, $index): float|int
    {
        return ($ru["ru_$index.tv_sec"] * 1000 + intval($ru["ru_$index.tv_usec"] / 1000))
            - ($rus["ru_$index.tv_sec"] * 1000 + intval($rus["ru_$index.tv_usec"] / 1000));
    }

    public function __toString()
    {
        return "CPU:&nbsp;" . $this->runTime($this->endTime, $this->startTime, "utime") .
            "&nbsp;ms&nbsp;</br>Syscall:&nbsp;" . $this->runTime($this->endTime, $this->startTime, "stime") .
            "&nbsp;ms";
    }
}
