<?php

namespace AdventOfCode\Common;

class Point
{
    public $x;
    public $y;

    const NORTH = 1;
    const EAST = 2;
    const SOUTH = 3;
    const WEST = 4;

    const DIRECTIONS = [self::NORTH, self::EAST, self::SOUTH, self::WEST];

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function move(int $direction)
    {
        if ($direction === self::NORTH) {
            $this->y += 1;
        }
        if ($direction === self::SOUTH) {
            $this->y -= 1;
        }
        if ($direction === self::WEST) {
            $this->x += 1;
        }
        if ($direction === self::EAST) {
            $this->x -= 1;
        }
    }

    public function isEqual(self $point) : bool
    {
        return $this->x === $point->x && $this->y === $point->y;
    }
}