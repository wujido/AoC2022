<?php

namespace Days\Day08;

class Vector
{
    private float $x;
    private float $y;

    public function __construct(float $x, float $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function add(Vector $vector): Vector
    {
        $x = $this->x + $vector->getX();
        $y = $this->y + $vector->getY();
        return new Vector($x, $y);
    }

    public function move(Direction $direction): Vector
    {
        return $this->add($direction->vector());
    }

    public function getSelfInField(array $field, $default = null)
    {
        return $field[$this->y][$this->x] ?? $default;
    }

    public function isSelfInField(array $field): bool
    {
        return !is_null($this->getSelfInField($field));
    }

    public function getX(): float
    {
        return $this->x;
    }

    public function getY(): float
    {
        return $this->y;
    }
}