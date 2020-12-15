<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;

class Day15 extends BaseDay
{
    public function execute()
    {
        $input = array_merge([null], $this->getInputArray(',')); // Force start from 1
        $spokenNumbers = @array_flip($input);

        for ($i = count($input); $i <= 30000000; $i++) {
            $speak = empty($spokenBefore) ? 0 : $i - 1 - $spokenBefore;
            $spokenBefore = $spokenNumbers[$speak] ?? null;
            $spokenNumbers[$speak] = $i;

            if ($i === 2020) {
                $this->part1 = $speak;
            }
        }
        $this->part2 = $speak;
    }
}
