<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;

class Day18 extends BaseDay
{
    public function execute()
    {
        foreach ($this->getInputArray() as $expression) {
            $this->part1 += $this->calculate($expression);
            $this->part2 += $this->calculate($expression, true);
        }
    }

    private function calculate(string $expression, bool $sumsFirst = false) : int
    {
        $expression = '('.$expression.')';
        $singlePattern = '/\d+ [' . ($sumsFirst ? '+' : '+*') . '] \d+/';
        while (preg_match('/\(([^()]+)\)/', $expression, $inner)) {
            while (preg_match($singlePattern, $inner[1], $single)) {
                $inner[1] = str_replace($single[0], math_eval($single[0]), $inner[1]);
            }
            $expression = str_replace($inner[0], math_eval($inner[1]), $expression);
        }
        return $expression;
    }
}