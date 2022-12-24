<?php

namespace Days\Day08;

enum Direction
{
    case Up;
    case Down;
    case Left;
    case Right;

    public function vector(): Vector
    {
        return match ($this) {
            self::Up => new Vector(0, 1),
            self::Down => new Vector(0, -1),
            self::Left => new Vector(-1, 0),
            self::Right => new Vector(1, 0),
        };
    }
}