<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;

class Day02 extends BaseDay
{
    public function execute()
    {
        $this->part1 = 0;
        $this->part2 = 0;
        foreach ($this->getInputArray("\n") as $row) {
            preg_match('/([0-9]*)-([0-9]*) (\w): (\w*)/', $row, $matches);
            list(, $n1, $n2, $letter, $password) = $matches;

            $letterCounts = array_count_values(str_split($password));
            if (!$letterCount = $letterCounts[$letter] ?? null) {
                continue;
            }

            if ($letterCount >= $n1 && $letterCount <= $n2) {
                $this->part1++;
            }

            if (($password[$n1 - 1] ?? null) === $letter xor ($password[$n2 - 1] ?? null) === $letter) {
                $this->part2++;
            }
        }
    }
}