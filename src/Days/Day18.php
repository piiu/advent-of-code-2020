<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;

class Day18 extends BaseDay
{
    public function execute()
    {
        foreach ($this->getInputArray(PHP_EOL) as $exp) {
            $this->part1 += $this->calculate($exp); // wrong: 30753705405211, right: 30753705453324
            $this->part2 += $this->calculate($exp, '+'); // wrong: 244817530095800, right: 244817530095503
        }
    }

    private function calculate(string $exp, string $first = '+*') : int
    {
        $exp = '('.$exp.')';
        while (preg_match('/\(([^()]+)\)/', $exp, $inner)) {
            while (preg_match("/\d+ [$first] \d+/", $inner[1], $single)) {
                $inner[1] = str_replace($single[0], math_eval($single[0]), $inner[1]);
            }
            $exp = str_replace($inner[0], math_eval($inner[1]), $exp);
        }
        return $exp;
    }
}