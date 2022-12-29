<?php

namespace Days\Day11;

use Closure;
use Ds\Queue;

class Monkey
{
    private Queue $items;
    private Closure $operation;
    private int $divisible;
    private int $true;
    private int $false;
    private int $inspected = 0;
    public static int $totalInspected = 0;

    private function __construct(Queue $items, Closure $operation, int $divisible, int $true, int $false)
    {

        $this->items = $items;
        $this->operation = $operation;
        $this->divisible = $divisible;
        $this->true = $true;
        $this->false = $false;
    }

    public static function fromArray($arr): Monkey
    {
        array_shift($arr); // Dump Monkey name

        $listSource = array_shift($arr);
        $listSource = explode(', ', self::getStrAfter($listSource, ':'));
        $list = new Queue($listSource);

        $operationSource = array_shift($arr);
        $operationSource = str_replace('old', 'o', self::getStrAfter($operationSource, '='));
        $operation = self::getOperation($operationSource);

        $divisible = self::getLastNumberFromStr(array_shift($arr));
        $true = self::getLastNumberFromStr(array_shift($arr));
        $false = self::getLastNumberFromStr(array_shift($arr));

        return new Monkey($list, $operation, $divisible, $true, $false);
    }

    private static function getStrAfter(mixed $listSource, string $after): string
    {
        return substr($listSource, strpos($listSource, $after) + 2);
    }

    private static function getLastNumberFromStr($text): string
    {
        preg_match('/(\d+)\D*$/', $text, $m);
        return $m[1];
    }

    public static function getOperation(array|string $operationSource): Closure
    {
        if (preg_match("/o \* o/", $operationSource)) {
            return fn($o) => $o * $o;
        }

        if (preg_match("/o \+ o/", $operationSource)) {
            return fn($o) => $o + $o;
        }

        if (preg_match("/o \* ([0-9]*)/", $operationSource, $matches)) {
            return fn($o) => $o * intval($matches[1]);
        }

        if (preg_match("/o \+ ([0-9]*)/", $operationSource, $matches)) {
            return fn($o) => $o + intval($matches[1]);
        }

        return fn($old) => math_eval($operationSource, ['o' => $old]);
    }

    public function simulateRound(Closure $itemProcessor): void
    {
       while (!$this->items->isEmpty()) {
           self::$totalInspected++;
           $this->inspected++;
           $itemProcessor($this->items->pop(), $this->operation, $this->divisible, $this->true, $this->false);
       }
    }

    public function addItem(int $item): void
    {
        $this->items->push($item);
    }

    public function Items(): string
    {
        return implode([', '], $this->items);
    }

    public function getInspected(): int
    {
        return $this->inspected;
    }

    public function getDivisible(): int
    {
        return $this->divisible;
    }
}