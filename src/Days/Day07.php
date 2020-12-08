<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;

class Day07 extends BaseDay
{
    private array $rules = [];

    const TARGET = 'shiny gold';

    public function execute()
    {
        $this->formatRules();
        foreach ($this->rules as $color => $rule) {
            if ($this->containsGoldBag($color)) {
                $this->part1++;
            }
        }
        $this->part2 = $this->getNumberOfContainedBags(self::TARGET);
    }

    private function containsGoldBag(string $bag) : bool
    {
        foreach ($this->rules[$bag] as $color => $number) {
            if ($color === self::TARGET || $this->containsGoldBag($color)) {
                return true;
            }
        }
        return false;
    }

    private function getNumberOfContainedBags(string $bag) : int
    {
        $total = 0;
        foreach ($this->rules[$bag] as $color => $number) {
            $total += $number + $number * $this->getNumberOfContainedBags($color);
        }
        return $total;
    }

    private function formatRules()
    {
        foreach ($this->getInputArray() as $rule) {
            preg_match('/(\w* \w*) bags contain (.*)\./', $rule, $matches);
            $color = $matches[1];
            $contents = $matches[2];
            $this->rules[$color] = [];
            if ($contents === 'no other bags') {
                continue;
            }
            foreach (explode(', ', $contents) as $innerBag) {
                preg_match('/(\d*) (\w* \w*) bags?/', $innerBag, $matches);
                $this->rules[$color][$matches[2]] = $matches[1];
            }
        }
    }
}