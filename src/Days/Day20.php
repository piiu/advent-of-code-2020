<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\Coordinates\Location;
use AdventOfCode\Common\Coordinates\Map;

class Day20 extends BaseDay
{
    /** @var Map[] */
    private array $tiles;
    private array $tileStates = [];
    private array $tileEdges = [];
    private int $width;
    private int $tileWidth;
    private array $cornerIds = [];
    private array $usedTiles = [];

    public function execute()
    {
        $this->loadTiles($this->getInputArray(PHP_EOL.PHP_EOL));
        $tilePositions = $this->getTilePositions();
        $this->part1 = array_product($this->cornerIds);

        $image = $this->buildImage($tilePositions);
        $image = $this->markSeaMonsters($image);
        $this->part2 = $image->getCountOfValue('#');

    }

    private function getTilePositions() : array
    {
        $positions = [];
        for ($row = 0; $row < $this->width; $row++) {
            for ($column = 0; $column < $this->width; $column++) {
                $sides = [
                    Location::UP => $row === 0 ? 'EDGE' : $positions[$row-1][$column]->getEdge(Location::DOWN),
                    Location::DOWN => $row === ($this->width - 1) ? 'EDGE' : null,
                    Location::LEFT => $column === 0 ? 'EDGE' : $positions[$row][$column-1]->getEdge(Location::RIGHT),
                    Location::RIGHT => $column === ($this->width - 1) ? 'EDGE' : null
                ];

                $positions[$row][$column] = $this->findFittingTile($sides);
            }
        }
        return $positions;
    }

    private function buildImage(array $tilePositions) : Map
    {
        $map = new Map();
        foreach ($tilePositions as $tileRow) {
            for ($i = 0; $i < $this->tileWidth - 1; $i++) {
                $row = [];
                foreach ($tileRow as $tile) {
                    $rowValues = $tile->getFirstRow();
                    if ($i === 0) {
                        continue;
                    }
                    $row = array_merge($row, array_slice($rowValues, 1, $this->tileWidth - 2));
                }
                if (!empty($row)) {
                    $map->addRow($row);
                }
            }
        }
        return $map;
    }

    private function markSeaMonsters(Map $image) : Map
    {
        $seaMonsterDefinition = $this->getSeaMonsterLocations();
        foreach ($this->getAllStates($image) as $state) {
            if ($this->mark($state, $seaMonsterDefinition)) {
                return $state;
            }
        }
        throw new \Exception('No monsters here!');
    }

    private function mark(Map $image, $seaMonsterLocation) : bool
    {
        $monsters = 0;
        $location = new Location();

        while($image->getValue($location)) {
            while($image->getValue($location)) {
                $sighting = true;
                $locationsToMark = [];
                foreach ($seaMonsterLocation as $sm) {
                    $newLocation = new Location($location->x + $sm->x, $location->y + $sm->y);
                    if ($image->getValue($newLocation) !== '#') {
                        $sighting = false;
                        break;
                    }
                    $locationsToMark[] = $newLocation;
                }
                if ($sighting) {
                    $monsters++;
                    foreach ($locationsToMark as $sm) {
                        $image->setValue($sm, 'O');
                    }
                }
                $location->move(Location::RIGHT);
            }
            $location = new Location(0, $location->y + 1);
        }

        return $monsters !== 0;
    }

    private function findFittingTile(array $sides) : Map
    {
        foreach ($this->tiles as $id => $tile) {
            if (in_array($id, $this->usedTiles)) {
                continue;
            }

            $edges = $this->getEdges($id, $tile);
            if (count($edges) !== $this->countEdges($sides)) {
                continue;
            }

            foreach ($this->tileStates[$id] as $tileState) {
                if ($this->fitsAllSides($tileState, $sides, $edges)) {
                    $this->setUsed($id, count($edges));
                    return $tileState;
                }
            }
        }
        throw new \Exception('No fitting tile');
    }

    private function setUsed($tileId, $corners)
    {
        $this->usedTiles[] = $tileId;
        if ($corners === 2) {
            $this->cornerIds[] = $tileId;
        }
    }

    private function countEdges(array $sides, int $count = 0) : int
    {
        foreach ($sides as $side) {
            if ($side === 'EDGE') $count++;
        }
        return $count;
    }

    private function fitsAllSides(Map $tile, array $sides, array $confirmedEdges) : bool
    {
        foreach ($sides as $direction => $side) {
            if (!$side) {
                continue;
            }
            $edge = $tile->getEdge($direction);

            if ($side === 'EDGE' && !$this->isIncluded($edge, $confirmedEdges)) {
                return false;
            }

            if ($side !== 'EDGE' && !$this->isSame($edge, $side, false)) {
                return false;
            }
        }
        return true;
    }

    private function isIncluded(array $side, array $listOfSides) : bool
    {
        foreach ($listOfSides as $comparison) {
            if ($this->isSame($side, $comparison)) {
                return true;
            }
        }
        return false;
    }

    private function isSame(array $s1, array $s2, bool $reverse = true) : bool
    {
        $s1 = array_values($s1);
        $s2 = array_values($s2);
        if ($reverse) {
            return $s1 === $s2 || $s1 === array_reverse($s2);
        } else {
            return $s1 === $s2;
        }

    }

    private function getEdges(int $id, Map $tile) : array
    {
        if (isset($this->tileEdges[$id])) {
            return $this->tileEdges[$id];
        }

        $sides = [];
        foreach (Location::DIRECTIONS as $direction) {
            $edge = $tile->getEdge($direction);
            if ($this->isEdge($id, $edge)) {
                $sides[] = $edge;
            }
            if (count($sides) === 2) {
                break;
            }
        }
        $this->tileEdges[$id] = $sides;
        return $sides;
    }

    private function isEdge(int $id, array $side) : bool
    {
        foreach ($this->tiles as $tileId => $tile) {
            if ($tileId === $id) {
                continue;
            }
            foreach (Location::DIRECTIONS as $direction) {
                $edge = $tile->getEdge($direction);
                if ($this->isSame($edge, $side)) {
                    return false;
                }
            }
        }
        return true;
    }

    private function loadTiles($input)
    {
        $input = array_reverse($input);
        foreach ($input as $item) {
            $rows = explode(PHP_EOL, $item);
            $id = preg_replace("/[^0-9]/", "", array_shift($rows));
            $tile = new Map(array_map('str_split', $rows));
            $this->tileWidth = $tile->getWidth() + 1;
            $this->tiles[$id] = $tile;

            $this->tileStates[$id] = $this->getAllStates($tile);
        }
        $this->width = sqrt(count($this->tiles));
    }

    private function getAllStates(Map $tile) : array
    {
        $states = [];
        for ($i = 0; $i <= 3; $i++) {
            $rotatedTile = $i === 0 ? $tile : $tile->getRotatedMap($i * 90);
            $states[] = $rotatedTile;
            foreach ([Map::AXIS_X, Map::AXIS_Y] as $axis) {
                $flippedTile = $rotatedTile->getFlipped($axis);
                $states[] = $flippedTile;
            }
        }
        return $states;
    }

    private function getSeaMonsterLocations() : array
    {
        $input = file_get_contents(__DIR__ . '\..\..\input\seamonster');
        $map = array_map(function ($row) {
            return str_split($row);
        }, explode(PHP_EOL, $input));
        return (new Map($map))->getLocationsOfValue('#');
    }
}