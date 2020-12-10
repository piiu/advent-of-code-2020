<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;

class Day10 extends BaseDay
{
    public function execute()
    {
        $adapters = $this->getInputArray(PHP_EOL);
        $adapters[] = 0;
        sort($adapters);

        $differences = [];
        $currentJolts = 0;
        foreach ($adapters as $index => $adapter) {
            $differences[] = $adapter - $currentJolts;
            $currentJolts = $adapter;
        }

        $counts = array_count_values($differences);
        $this->part1 = $counts['1'] * ($counts['3'] + 1);

        $pathsToHere = [];
        foreach ($adapters as $index => $adapter) {
            $pathsToHere[$index] = $adapter == 0 ? 1 : 0;

            foreach (range(3,1) as $indexDiff) {
                $previousIndex = $index - $indexDiff;
                $previous = $adapters[$index - $indexDiff] ?? null;
                if ($previous !== null && $previous >= $adapter - 3) {
                    $pathsToHere[$index] += $pathsToHere[$previousIndex];
                }
            }
        }

        $this->part2 = array_pop($pathsToHere);
    }
}