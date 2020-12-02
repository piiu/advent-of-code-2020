<?php

namespace AdventOfCode\Common;

abstract class BaseDay
{
    private $input;
    private $dayNumber;
    protected $part1;
    protected $part2;

    const INVALID_ANSWER = 'Something went wrong';

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
        $this->output([
            "######## Day $this->dayNumber ########",
            'Part 1: ' . $this->part1 ?? self::INVALID_ANSWER,
            'Part 2: ' . $this->part2 ?? self::INVALID_ANSWER
        ]);
    }

    protected function output(array $data)
    {
        foreach ($data as $line) {
            echo $line . PHP_EOL;
        }
    }

    protected function getInputArray(string $delimiter) : array
    {
        return explode($delimiter, $this->input);
    }

    protected function getInputMap() : Map
    {
        $rows = explode(PHP_EOL, $this->input);
        $map = array_map(function ($row) {
            return str_split($row);
        }, $rows);
        return new Map($map);
    }
}