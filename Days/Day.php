<?php
namespace Days;

interface Day
{
    public function run(array $input): int|string;
}