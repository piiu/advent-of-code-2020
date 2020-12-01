<?php

namespace AdventOfCode\Common;

abstract class BaseDay {
    private $input;
    private $dayNumber;

    const INVALID_ANSWER = 'Something went wrong';

    public function __construct() {
        $dayNumber = preg_replace("/[^0-9]/", "", get_class($this));
        $inputFile = __DIR__ . '\..\Input\day' . $dayNumber;
        if (file_exists($inputFile)) {
            $this->input = file_get_contents($inputFile);

        }
        $this->dayNumber = (int)$dayNumber;
    }

    public abstract function getPart1();
    public abstract function getPart2();

    public function results() {
        echo 'Day ' . $this->dayNumber . PHP_EOL;
        echo 'Part 1: ' . $this->getPart1() . PHP_EOL;
        echo 'Part 2: ' . $this->getPart2() . PHP_EOL;
    }

    protected function getInputArray($delimiter) {
        return explode($delimiter, $this->input);
    }
}