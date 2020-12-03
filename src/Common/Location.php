<?php

namespace AdventOfCode\Common;

class Location
{
    public $x;
    public $y;

    const UP = 1;
    const DOWN = 2;
    const LEFT = 3;
    const RIGHT = 4;

    const DIRECTIONS = [self::UP, self::DOWN, self::LEFT, self::RIGHT];

    public function __construct(int $x = 0, int $y = 0)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function move(int $direction, int $amount = 1) : self
    {
        if ($direction === self::UP) {
            $this->y -= $amount;
        }
        if ($direction === self::DOWN) {
            $this->y += $amount;
        }
        if ($direction === self::LEFT) {
            $this->x -= $amount;
        }
        if ($direction === self::RIGHT) {
            $this->x += $amount;
        }
        return $this;
    }

    public function isEqual(self $point) : bool
    {
        return $this->x === $point->x && $this->y === $point->y;
    }
}