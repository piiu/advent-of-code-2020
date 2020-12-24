<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;

class Day24 extends BaseDay
{
    private array $tiles = [];

    const ALL_DIRECTIONS = ['nw', 'ne', 'e', 'se', 'sw', 'w'];

    public function execute()
    {
        $this->getTiles($this->getInputArray(PHP_EOL));

        $this->part1 = $this->countBlacks();

        for ($i=0; $i<100; $i++) {
            $this->transform();
        }

        $this->part2 = $this->countBlacks();
    }

    private function getTiles(array $tileDefinitions)
    {
        foreach ($tileDefinitions as $path) {
            $coordinates = $this->getTileCoordinates($path);
            $value = $this->get($coordinates);
            $this->set($coordinates, (!$value || $value === 'W') ? 'B' : 'W');
        }
    }

    private function getTileCoordinates($path) : array
    {
        $coordinates = ['x' => 0, 'y' => 0, 'z' => 0];
        $memory = '';
        foreach (str_split($path) as $letter) {
            if (in_array($letter, ['n', 's'])) {
                $memory = $letter;
                continue;
            }
            $direction = $memory . $letter;
            $coordinates = $this->getNewCoordinates($coordinates, $direction);
            $memory = '';
        }
        return $coordinates;
    }

    private function getNewCoordinates(array $coordinates, string $direction) : array
    {
        switch ($direction) {
            case 'nw':
                $coordinates['y']++;
                $coordinates['z']--;
                return $coordinates;
            case 'ne':
                $coordinates['x']++;
                $coordinates['z']--;
                return $coordinates;
            case 'e':
                $coordinates['x']++;
                $coordinates['y']--;
                return $coordinates;
            case 'se':
                $coordinates['y']--;
                $coordinates['z']++;
                return $coordinates;
            case 'sw':
                $coordinates['x']--;
                $coordinates['z']++;
                return $coordinates;
            case 'w':
                $coordinates['x']--;
                $coordinates['y']++;
                return $coordinates;
            default:
                throw new \Exception('Invalid direction');
        }
    }

    private function getNumberOfBlackNeighbours(array $coordinates) : int
    {
        $blacks = 0;
        foreach(self::ALL_DIRECTIONS as $direction) {
            $neighbour = $this->getNewCoordinates($coordinates, $direction);
            if ($this->get($neighbour) === 'B') {
                $blacks++;
            }
        }
        return $blacks;
    }

    private function transform()
    {
        $newState = $this->tiles;
        foreach ($newState as $x => $xTiles) {
            foreach ($xTiles as $y => $yTiles) {
                foreach ($yTiles as $z => $value) {
                    $coordinates = ['x' => $x, 'y' => $y, 'z' => $z];
                    $oldValue = $this->get($coordinates);
                    $this->setInState($newState, $coordinates, $this->getNewValue($oldValue, $coordinates));

                    if ($oldValue !== 'B') {
                        continue;
                    }

                    foreach (self::ALL_DIRECTIONS as $direction) {
                        $neighbourCoordinates = $this->getNewCoordinates($coordinates, $direction);
                        if (!$this->get($neighbourCoordinates) && $this->getNewValue('W', $neighbourCoordinates) === 'B') {
                            $this->setInState($newState, $neighbourCoordinates, 'B');
                        }
                    }
                }
            }
        }
        $this->tiles = $newState;
    }

    private function getNewValue(string $oldValue, array $coordinates) : string
    {
        $numberOfNeighbours = $this->getNumberOfBlackNeighbours($coordinates);
        if ($oldValue === 'B' && ($numberOfNeighbours === 0 ||$numberOfNeighbours > 2)) {
            return 'W';
        } elseif ($oldValue === 'W' && $numberOfNeighbours === 2) {
            return 'B';
        }
        return $oldValue;
    }

    private function countBlacks() : int
    {
        $blacks = 0;
        foreach ($this->tiles as $x => $xTiles) {
            foreach ($xTiles as $y => $yTiles) {
                foreach ($yTiles as $z => $value) {
                    if ($value === 'B') {
                        $blacks++;
                    }
                }
            }
        }
        return $blacks;
    }

    private function get(array $coordinates) : ?string
    {
        return $this->tiles[$coordinates['x']][$coordinates['y']][$coordinates['z']] ?? null;
    }

    private function set( array $coordinates, string $value) {
        $this->setInState($this->tiles, $coordinates, $value);
    }

    private function setInState(array &$state, array $coordinates, string $value)
    {
        $state[$coordinates['x']][$coordinates['y']][$coordinates['z']] = $value;
    }
}