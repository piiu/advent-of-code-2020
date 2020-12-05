<?php


namespace AdventOfCode\Days;


use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\Location;
use AdventOfCode\Common\Map;

class Day05 extends BaseDay
{
    private $seatMap;
    private $minRow;
    private $maxRow;

    const TOTAL_ROWS = 128;
    const TOTAL_COLUMNS = 8;

    public function __construct()
    {
        parent::__construct();
        $this->seatMap = new Map([]);
    }

    public function execute()
    {
        foreach ($this->getInputArray() as $boardingPass) {
            $seat = $this->getSeat($boardingPass);
            $this->updateMinMaxValues($seat);
            $this->seatMap->setValue($seat, 'X');
        }

        for ($column = 0; $column <= 8; $column++) {
            for ($row = $this->minRow; $row <= $this->maxRow; $row++) {
                $seatTaken = $this->isSeatTaken($row, $column);
                if ($seatTaken) {
                    continue;
                }
                if ($row === $this->minRow && !$this->isSeatTaken($row, $column - 1)) {
                    // This is the start of the plane
                    continue;
                }
                $this->part2 =$this->getSeatId($row, $column);
                return;
            }
        }
    }

    private function isSeatTaken(int $row, int $column) : bool
    {
        return !empty($this->seatMap->getValue(new Location($row, $column)));
    }

    private function getSeat(string $boardingPass) : Location
    {
        $row = range(0, self::TOTAL_ROWS - 1);
        $column = range(0, self::TOTAL_COLUMNS - 1);
        foreach (str_split($boardingPass) as $letter) {
            if ($letter === 'F') {
                $this->getLowerHalf($row);
            }
            if ($letter === 'B') {
                $this->getUpperHalf($row);
            }
            if ($letter === 'L') {
                $this->getLowerHalf($column);
            }
            if ($letter === 'R') {
                $this->getUpperHalf($column);
            }
        }
        return new Location($row[0], $column[0]);
    }

    private function getSeatId(int $row, int $column) : int
    {
        return $row * 8 + $column;
    }

    private function updateMinMaxValues(Location $seat)
    {
        $seatId = $this->getSeatId($seat->x, $seat->y);
        if (!$this->part1 || $this->part1 < $seatId) {
            $this->part1 = $seatId;
        }
        if ($this->minRow === null || $this->minRow > $seat->x) {
            $this->minRow = $seat->x;
        }
        if ($this->maxRow === null || $this->maxRow < $seat->x) {
            $this->maxRow = $seat->x;
        }
    }

    private function getUpperHalf(array &$range)
    {
        $first = array_shift($range);
        $last = array_pop($range);
        $middle = ceil(($first + $last) / 2);
        $range = range($middle, $last);
    }

    private function getLowerHalf(array &$range)
    {
        $first = array_shift($range);
        $last = array_pop($range);
        $middle = floor(($first + $last) / 2);
        $range = range($first, $middle);
    }
}