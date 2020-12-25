<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;

class Day25 extends BaseDay
{
    public function execute()
    {
        list($key1, $key2) = $this->getInputArray(PHP_EOL);
        $loop1 = $this->findLoopSize(7, $key1);

        $value = 1;
        for ($i = 0; $i<$loop1; $i++) {
            $value = $this->transform($key2, $value);
        }
        $this->part1 = $value;
        $this->part2 = 'Happy holidays!';
    }

    private function findLoopSize(int $subject, int $key, int $loopSize = 0) : int
    {
        $value = 1;
        while ($value !== $key) {
            $loopSize++;
            $value = $this->transform($subject, $value);
        }
        return $loopSize;
    }

    private function transform(int $subject, int $value) : int
    {
        return ($subject * $value) % 20201227;
    }
}