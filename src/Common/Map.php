<?php

namespace AdventOfCode\Common;

class Map
{
    private $map;

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

    public function draw(array $definitions = [])
    {
        $output = '';
        $this->getExtremes($minX, $maxX, $minY, $maxY);
        for ($y = $minY; $y <= $maxY; $y++) {
            for ($x = $minX; $x <= $maxX; $x++) {
                if (!$value = $this->getValue(new Location($x, $y))) {
                    $output .= ' ';
                    continue;
                }
                $output .= $definitions[$value] ?? $value;
            }
            $output .= PHP_EOL;
        }
        echo $output;
    }

    private function getExtremes(int &$minX = null, int &$maxX = null, int &$minY = null, int &$maxY = null)
    {
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
}