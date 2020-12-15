<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;

class Day15 extends BaseDay
{
    public function execute()
    {
        $input = array_merge([''], $this->getInputArray(',')); // Force start from 1
        $lastSpoken = array_flip($input);

        for ($i = count($input); $i <= 30000000; $i++) {
            $speak = empty($lastSpokenTurn) ? 0 : $i - 1 - $lastSpokenTurn;
            $lastSpokenTurn = $lastSpoken[$speak] ?? null;
            $lastSpoken[$speak] = $i;

            if ($i === 2020) {
                $this->part1 = $speak;
            }
        }
        $this->part2 = $speak;
    }
}
