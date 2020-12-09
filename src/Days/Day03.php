<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\Coordinates\Location;

class Day03 extends BaseDay
{
    const TREE = '#';
    const SLOPES = [
        [1,1],
        [3,1],
        [5,1],
        [7,1],
        [1,2]
    ];

    public function execute()
    {
        $map = $this->getInputMap();
        $width = $map->getWidth();

        $counts = [];
        foreach (self::SLOPES as $slope) {
            list($right, $down) = $slope;
            $location = new Location();
            $count = 0;
            do {
                $location->move(Location::RIGHT, $right)->move(Location::DOWN, $down);
                $location->x = $location->x % $width;
                if ($map->getValue($location) === self::TREE) {
                    $count++;
                }
            } while ($map->getValue($location));
            $counts[] = $count;
        }

        $this->part1 = $counts[1];
        $this->part2 = array_product($counts);
    }
}