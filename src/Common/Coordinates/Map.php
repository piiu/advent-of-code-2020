<?php

namespace AdventOfCode\Common\Coordinates;

use AdventOfCode\Console\Utils;

class Map
{
    private array $map;
    private bool $isSquare = true;

    public function __construct(array $map)
    {
        $this->map = $map;
    }

    public function getValue(Location $point) : ?string
    {
        return $this->map[$point->y][$point->x] ?? null;
    }

    public function setValue(Location $point, string $value)
    {
        $this->map[$point->y][$point->x] = $value;
    }

    public function draw(array $output = [])
    {
        $this->getExtremes($minX, $maxX, $minY, $maxY);
        for ($y = $minY; $y <= $maxY; $y++) {
            $row = '';
            for ($x = $minX; $x <= $maxX; $x++) {
                $row .= $this->getValue(new Location($x, $y)) ?? ' ';
            }
            $output[] = $row;
        }
        Utils::outputArray($output);
    }

    public function getWidth() : int
    {
        $this->getExtremes($minX, $maxX);
        return $maxX - $minX;
    }

    public function getHeight() : int
    {
        $this->getExtremes($minX, $maxX, $minY, $maxY);
        return $maxY - $minY;
    }

    private function getExtremes(int &$minX = null, int &$maxX = null, int &$minY = null, int &$maxY = null)
    {
        if ($this->isSquare) {
            $this->getSquareExtremes($minX, $maxX, $minY, $maxY);
            return;
        }

        foreach ($this->map as $row) {
            foreach (array_keys($row) as $index) {
                if ($minX === null || $minX > $index) {
                    $minX = $index;
                }
                if ($maxX === null || $maxX < $index) {
                    $maxX = $index;
                }
            }
        }

        $minY = min(array_keys($this->map));
        $maxY = max(array_keys($this->map));
    }

    private function getSquareExtremes(int &$minX = null, int &$maxX = null, int &$minY = null, int &$maxY = null)
    {
        $minX = 0;
        $maxX = count($this->map[0]) - 1;
        $minY = 0;
        $maxY = count($this->map) - 1;
    }

    public function setNotSquare()
    {
        $this->isSquare = true;
    }

    public function getCountOfValue(string $value) : int
    {
        $count = 0;
        $this->getExtremes($minX, $maxX, $minY, $maxY);
        for ($y = $minY; $y <= $maxY; $y++) {
            for ($x = $minX; $x <= $maxX; $x++) {
                if ($this->getValue(new Location($x, $y)) === $value) {
                    $count++;
                }
            }
        }
        return $count;
    }
}