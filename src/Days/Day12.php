<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\Coordinates\Location;

class Day12 extends BaseDay
{
    public function execute()
    {
        $instructions = $this->getInstructions();

        $location1 = $this->fly1($instructions, new Location(), 'E');
        $this->part1 = $location1->getManhattanDistance();

        $location2 = $this->fly2($instructions, new Location(), new Location(10, -1));
        $this->part2 = $location2->getManhattanDistance();
    }

    private function fly1(array $instructions, Location $location, string $direction) : Location
    {
        $direction = Location::COMPASS_TO_DIRECTION[$direction];
        foreach ($instructions as $instruction) {
            /**
             * @var string $letter
             * @var int $number
             */
            extract($instruction);
            if ($letter === 'F') {
                $location->move($direction, $number);
                continue;
            }
            if (in_array($letter, ['L', 'R'])) {
                $this->modifyDirection($direction, $number, $letter);
                continue;
            }
            $location->move(Location::COMPASS_TO_DIRECTION[$letter], $number);
        }
        return $location;
    }

    private function fly2(array $instructions, Location $location, Location $waypoint) : Location
    {
        foreach ($instructions as $instruction) {
            /**
             * @var string $letter
             * @var int $number
             */
            extract($instruction);
            if ($letter === 'F') {
                $location->move(Location::COMPASS_TO_DIRECTION['E'], $number * $waypoint->x);
                $location->move(Location::COMPASS_TO_DIRECTION['S'], $number * $waypoint->y);
                continue;
            }
            if (in_array($letter, ['L', 'R'])) {
                $waypoint->rotate($number, $letter);
                continue;
            }
            $waypoint->move(Location::COMPASS_TO_DIRECTION[$letter], $number);
        }
        return $location;
    }

    private function getInstructions() : array
    {
        return array_map(function($row) {
            preg_match('/([A-Z])([0-9]*)/', $row, $matches);
            return [
                'letter' => $matches[1],
                'number' => $matches[2]
            ];
        }, $this->getInputArray(PHP_EOL));
    }

    private function modifyDirection(int &$currentDirection, int $degrees, string $turnDirection)
    {
        $currentIndex = array_search($currentDirection, Location::DIRECTIONS);
        if ($turnDirection === 'R') {
            $newIndex = ($currentIndex + $degrees / 90) % 4;
        } else {
            $newIndex = ($currentIndex + 4 - $degrees / 90) % 4;
        }
        $currentDirection = Location::DIRECTIONS[$newIndex];
    }
}