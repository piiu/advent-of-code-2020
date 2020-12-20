<?php


namespace AdventOfCode\Days;


use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\Coordinates\Location;
use AdventOfCode\Common\Coordinates\Map;

class Day05 extends BaseDay
{
    private Map $seatMap;

    const TOTAL_ROWS = 128;
    const TOTAL_COLUMNS = 8;

    public function __construct()
    {
        parent::__construct();
        $this->seatMap = new Map();
    }

    public function execute()
    {
        $this->populateSeatMap();
        $this->findAnEmptySeat();
    }

    private function populateSeatMap()
    {
        foreach ($this->getInputArray() as $boardingPass) {
            $seat = $this->getSeat($boardingPass);
            $seatId = $this->getSeatId($seat);
            if (!$this->part1 || $this->part1 < $seatId) {
                $this->part1 = $seatId;
            }
            $this->seatMap->setValue($seat, 'X');
        }
    }

    private function findAnEmptySeat()
    {
        for ($column = 0; $column < self::TOTAL_COLUMNS; $column++) {
            for ($row = 0; $row < self::TOTAL_ROWS; $row++) {
                $seat = new Location($row, $column);
                if ($this->isSeatTaken($seat)) {
                    $seatsStarted = true;
                    continue;
                }
                if (!empty($seatsStarted)) {
                    $this->part2 =$this->getSeatId($seat);
                    return;
                }
            }
        }
    }

    private function getSeat(string $boardingPass) : Location
    {
        $row = range(0, self::TOTAL_ROWS - 1);
        $column = range(0, self::TOTAL_COLUMNS - 1);
        foreach (str_split($boardingPass) as $letter) {
            if ($letter === 'F') {
                $row = $this->getLowerHalf($row);
            }
            if ($letter === 'B') {
                $row = $this->getUpperHalf($row);
            }
            if ($letter === 'L') {
                $column = $this->getLowerHalf($column);
            }
            if ($letter === 'R') {
                $column = $this->getUpperHalf($column);
            }
        }
        return new Location($row[0], $column[0]);
    }

    private function getSeatId(Location $seat) : int
    {
        return $seat->x * 8 + $seat->y;
    }

    private function isSeatTaken(Location $seat) : bool
    {
        return !empty($this->seatMap->getValue($seat));
    }

    private function getUpperHalf(array $range) : array
    {
        $first = array_shift($range);
        $last = array_pop($range);
        $middle = ceil(($first + $last) / 2);
        return range($middle, $last);
    }

    private function getLowerHalf(array $range) : array
    {
        $first = array_shift($range);
        $last = array_pop($range);
        $middle = floor(($first + $last) / 2);
        return range($first, $middle);
    }
}