<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;

class Day19 extends BaseDay
{
    private array $rules = [];

    public function execute()
    {
        list($ruleBlock, $messageBlock) = $this->getInputArray(PHP_EOL.PHP_EOL);
        $this->formatRules($ruleBlock);
        $messages = explode(PHP_EOL, $messageBlock);

        $this->part1 = $this->getNumberOfMatches($messages);

        $this->rules[8] = '42 | 42 8';
        $this->rules[11] = '42 31 | 42 11 31';

        $this->part2 = $this->getNumberOfMatches($messages);
    }

    private function getNumberOfMatches(array $messages) : int
    {
        $matches = 0;
        foreach ($messages as $message) {
            $testIndex = 0;
            if ($this->matches(str_split($message), $this->rules[0], $testIndex) && $testIndex >= strlen($message)) {
                $matches++;
            }
        }
        return $matches;
    }

    private function matches(array $message, string $rule, int &$testIndex) : int
    {
        if (strlen($rule) === 1 && !is_numeric($rule)) {
            if (empty($message[$testIndex]) || $message[$testIndex] === $rule) {
                $testIndex++;
                return true;
            }
            return false;
        }
        $currentIndex = $testIndex;
        foreach (explode(' | ', $rule) as $option) {
            $testIndex = $currentIndex;
            $allMatch = true;
            foreach (explode(' ', $option) as $ruleIndex) {
                if (!$this->matches($message, $this->rules[$ruleIndex], $testIndex)) {
                    $allMatch = false;
                    break;
                }
            }
            if ($allMatch) {
                return true;
            }
        }
        return false;
    }

    private function formatRules(string $ruleBlock)
    {
        foreach (explode(PHP_EOL, $ruleBlock) as $ruleRow) {
            $parts = explode(': ', $ruleRow);
            $this->rules[$parts[0]] = str_replace('"', '', $parts['1']);
        }
    }
}