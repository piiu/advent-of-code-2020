<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\Coordinates\Location;
use AdventOfCode\Common\Coordinates\Map;

class Day11 extends BaseDay
{
    const FLOOR = '.';
    const EMPTY = 'L';
    const OCCUPIED = '#';

    public function execute()
    {
        $initialState = $this->getInputMap();
        $this->part1 = $this->getOccupiedSeats($initialState, 4, false);
        $this->part2 = $this->getOccupiedSeats($initialState, 5, true);
    }

    private function getOccupiedSeats(Map $previousState, $tolerance, $seeFar) : int
    {
        do {
            $hasChanged = false;
            $nextState = clone($previousState);
            for ($y = 0; $y <= $previousState->getHeight(); $y++) {
                for ($x = 0; $x <= $previousState->getWidth(); $x++) {
                    $currentLocation = new Location($x, $y);
                    $currentState = $previousState->getValue($currentLocation);
                    if (!$seeFar && $currentState === self::FLOOR) {
                        continue;
                    }
                    $occupiedSeats = $this->countOccupiedSeats($previousState, $currentLocation, $seeFar);
                    if ($currentState === self::EMPTY && !$occupiedSeats) {
                        $nextState->setValue($currentLocation, self::OCCUPIED);
                        $hasChanged = true;
                    }
                    if ($currentState === self::OCCUPIED && $occupiedSeats >= $tolerance) {
                        $nextState->setValue($currentLocation, self::EMPTY);
                        $hasChanged = true;
                    }
                }
            }
            $previousState = $nextState;
        } while ($hasChanged);

        return $nextState->getCountOfValue(self::OCCUPIED);
    }

    private function countOccupiedSeats(Map $state, Location $currentLocation, bool $seeFar) : int
    {
        $occupiedSeats = 0;
        foreach (Location::DIRECTIONS_WITH_DIAGONALS as $checkDirections) {
            $checkLocation = clone($currentLocation);
            do {
                $checkLocation->moveMultiple($checkDirections);
                $checkValue = $state->getValue($checkLocation);
            } while ($seeFar && $checkValue && $checkValue === self::FLOOR);

            if ($checkValue === self::OCCUPIED) {
                $occupiedSeats++;
            }
        }
        return $occupiedSeats;
    }

}