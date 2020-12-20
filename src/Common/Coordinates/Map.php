<?php

namespace AdventOfCode\Common\Coordinates;

use AdventOfCode\Console\Utils;

class Map
{
    private array $map;
    private bool $isSquare = true;

    const AXIS_X = 'x';
    const AXIS_Y = 'y';

    public function __construct(array $map = [])
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
        $minX = min(array_keys($this->map[0]));
        $maxX = max(array_keys($this->map[0]));
        $minY = min(array_keys($this->map));
        $maxY = max(array_keys($this->map));
    }

    public function setNotSquare()
    {
        $this->isSquare = false;
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

    public function getLocationsOfValue(string $value) : array
    {
        $locations = [];
        $this->getExtremes($minX, $maxX, $minY, $maxY);
        for ($y = $minY; $y <= $maxY; $y++) {
            for ($x = $minX; $x <= $maxX; $x++) {
                $location = new Location($x, $y);
                if ($this->getValue($location) === $value) {
                    $locations[] = $location;
                }
            }
        }
        return $locations;
    }

    public function getRotatedMap(int $degrees = 90) : self
    {
        $newMap = new self();
        $this->getExtremes($minX, $maxX, $minY, $maxY);
        for ($y = $minY; $y <= $maxY; $y++) {
            for ($x = $minX; $x <= $maxX; $x++) {
                $newLocation = (new Location($x, $y))->rotate($degrees);
                $newMap->setValue($newLocation, $this->getValue(new Location($x, $y)));
            }
        }
        $newMap->sort();
        return $newMap;
    }

    public function getFlipped(string $axis) : self
    {
        $newMap = new self();
        $this->getExtremes($minX, $maxX, $minY, $maxY);
        for ($y = $minY; $y <= $maxY; $y++) {
            for ($x = $minX; $x <= $maxX; $x++) {
                $newLocation = $axis === self::AXIS_X ? new Location(-$x, $y) : new Location($x, -$y);
                $newMap->setValue($newLocation, $this->getValue(new Location($x, $y)));
            }
        }
        $newMap->sort();
        return $newMap;
    }

    public function getEdge(int $direction) : array
    {
        $this->getExtremes($minX, $maxX, $minY, $maxY);
        switch ($direction) {
            case Location::UP:
                return $this->map[$minY];
            case Location::DOWN:
                return $this->map[$maxY];
            case Location::LEFT:
                return array_map(function ($row) use($minX) {
                        return $row[$minX];
                    }, $this->map);
            case Location::RIGHT:
                return array_map(function ($row) use($maxX) {
                    return $row[$maxX];
                }, $this->map);
        }
        throw new \Exception('Invalid direction');
    }

    public function sort()
    {
        foreach (array_keys($this->map) as $row) {
            ksort($this->map[$row]);
        }
        ksort($this->map);
    }

    public function getFirstRow() {
        return array_shift($this->map);
    }

    public function addRow(array $row) {
        $this->map[] = $row;
    }
}