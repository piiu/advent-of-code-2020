<?php

namespace AdventOfCode\Common;

abstract class BaseDay
{
    private $input;
    private $dayNumber;

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

    public abstract function getPart1() : string;
    public abstract function getPart2() : string;

    public function results()
    {
        $this->output([
            'Day ' . $this->dayNumber,
            'Part 1: ' . $this->getPart1(),
            'Part 2: ' . $this->getPart2()
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