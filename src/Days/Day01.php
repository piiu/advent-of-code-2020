<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;

class Day01 extends BaseDay {

    private $numbers;

    public function __construct() {
        parent::__construct();
        $this->numbers = $this->getInputArray("\n");
    }

    public function getPart1(): string {
        foreach ($this->numbers as $i1) {
            foreach ($this->numbers as $i2) {
                if ($i1 + $i2 == 2020) {
                    return $i1 * $i2;
                }
            }
        }
        return self::INVALID_ANSWER;
    }

    public function getPart2(): string {
        foreach ($this->numbers as $i1) {
            foreach ($this->numbers as $i2) {
                foreach ($this->numbers as $i3) {
                    if ($i1 + $i2 + $i3 == 2020) {
                        return $i1 * $i2 * $i3;
                    }
                }
            }
        }
        return self::INVALID_ANSWER;
    }
}