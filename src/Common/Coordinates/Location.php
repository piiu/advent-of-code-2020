<?php

namespace AdventOfCode\Common\Coordinates;

class Location
{
    public int $x;
    public int $y;

    const UP = 1;
    const DOWN = 2;
    const LEFT = 3;
    const RIGHT = 4;

    const DIRECTIONS = [self::UP, self::DOWN, self::LEFT, self::RIGHT];
    const DIRECTIONS_WITH_DIAGONALS = [
        [self::UP],
        [self::UP, self::RIGHT],
        [self::RIGHT],
        [self::RIGHT, self::DOWN],
        [self::DOWN],
        [self::DOWN, self::LEFT],
        [self::LEFT],
        [self::LEFT, self::UP]
    ];

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

    public function moveMultiple(array $directions = []) : self
    {
        foreach ($directions as $direction) {
            $this->move($direction);
        }
        return $this;
    }

    public function isEqual(self $location) : bool
    {
        return $this->x === $location->x && $this->y === $location->y;
    }
}