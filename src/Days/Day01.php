<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;

class Day01 extends BaseDay
{
    public function execute()
    {
        $numbers = $this->getInputArray("\n");
        foreach ($numbers as $i1) {
            foreach ($numbers as $i2) {
                if (!$this->part1 && $i1 + $i2 === 2020) {
                    $this->part1 = $i1 * $i2;
                }
                if (!$this->part2) {
                    foreach ($numbers as $i3) {
                        if ($i1 + $i2 + $i3 === 2020) {
                            $this->part2 = $i1 * $i2 * $i3;
                            break;
                        }
                    }
                }
                if ($this->part1 && $this->part2) {
                    break 2;
                }
            }
        }
    }
}