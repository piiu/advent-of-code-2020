<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;

class Day09 extends BaseDay
{
    public function execute()
    {
        $input = $this->getInputArray(PHP_EOL);
        foreach ($input as $index => $number) {
            if ($index<25) {
                continue;
            }
            $hasMatch = false;
            foreach (array_slice($input, $index-25, 25) as $term1) {
                foreach (array_slice($input, $index-25, 25) as $term2) {
                    if ($term1+$term2 == $number) {
                        $hasMatch = true;
                        break 2;
                    }
                }
            }
            if (!$hasMatch) {
                $this->part1 = $number;
                break;
            }
        }

        foreach ($input as $index => $number) {
            $set = [$number];
            do {
                $index++;
                if ($index >= 1000) {
                    break;
                }
                $set[] = $input[$index];
                $sum = array_sum($set);
                if ($sum == $this->part1) {
                    $this->part2 = min($set) + max($set);
                    break 2;
                }
            } while ($sum < $this->part1);
        }
    }
}