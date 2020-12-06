<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;

class Day06 extends BaseDay
{
    public function execute()
    {
        $groups = $this->getInputArray(PHP_EOL.PHP_EOL);
        foreach ($groups as $group) {
            $persons = explode(PHP_EOL, $group);
            $uniqueAnswers = [];
            $trueAnswers = range('a', 'z');
            foreach ($persons as $person) {
                $answers = str_split($person);
                $uniqueAnswers = array_unique(array_merge($uniqueAnswers, $answers));
                $trueAnswers = array_intersect($trueAnswers, $answers);
            }
            $this->part1 += count($uniqueAnswers);
            $this->part2 += count($trueAnswers);
        }
    }
}