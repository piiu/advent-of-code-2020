<?php

namespace AdventOfCode\Common;

use AdventOfCode\Console\Utils;

abstract class BaseDay
{
    private $dayNumber;
    protected $input;
    protected $part1;
    protected $part2;

    public function __construct()
    {
        $dayNumber = preg_replace("/[^0-9]/", "", get_class($this));
        $inputFile = __DIR__ . '\..\Input\day' . $dayNumber;
        if (file_exists($inputFile)) {
            $this->input = file_get_contents($inputFile);
        }
        $this->dayNumber = (int)$dayNumber;
    }

    public abstract function execute();

    public function results()
    {
        $this->execute();
        Utils::outputArray([
            "######## Day $this->dayNumber ########",
            'Part 1: ' . $this->part1,
            'Part 2: ' . $this->part2
        ]);
    }

    protected function getInputArray(string $delimiter = "\n") : array
    {
        return explode($delimiter, $this->input);
    }

    protected function getInputMap() : Map
    {
        $map = array_map(function ($row) {
            return str_split($row);
        }, $this->getInputArray());
        return new Map($map);
    }
}