<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;

class Day19 extends BaseDay
{
    public function execute()
    {
        list($ruleBlock, $messageBlock) = $this->getInputArray(PHP_EOL.PHP_EOL);
        $rules = $this->formatRules($ruleBlock);
        $messages = explode(PHP_EOL, $messageBlock);

        $this->part1 = $this->getNumberOfMatches($rules, $messages);

        $rules[8] = '42 | 42 8';
        $rules[11] = '42 31 | 42 11 31';

        $this->part2 = $this->getNumberOfMatches($rules, $messages);
    }

    private function getNumberOfMatches(array $rules, array $messages) : int
    {
        $matches = 0;
        foreach ($messages as $message) {
            $testIndex = 0;
            if ($this->matches(str_split($message), $rules, $rules[0], $testIndex) && $testIndex >= strlen($message)) {
                $matches++;
            }
        }
        return $matches;
    }

    private function matches(array $message, array $rules, string $rule, int &$testIndex) : int
    {
        if (in_array($rule, ['a', 'b'])) {
            if (empty($message[$testIndex]) || $message[$testIndex] === $rule) {
                $testIndex++;
                return true;
            }
        } else {
            $currentIndex = $testIndex;
            foreach (explode(' | ', $rule) as $option) {
                $testIndex = $currentIndex; // reset
                $allMatch = true;
                foreach (explode(' ', $option) as $ruleIndex) {
                    if (!$this->matches($message, $rules, $rules[$ruleIndex], $testIndex)) {
                        $allMatch = false;
                        break;
                    }
                }
                if ($allMatch) {
                    return true;
                }
            }
        }
        return false;
    }

    private function formatRules(string $ruleBlock) : array
    {
        $rules = [];
        foreach (explode(PHP_EOL, $ruleBlock) as $ruleRow) {
            $parts = explode(': ', $ruleRow);
            $rules[$parts[0]] = str_replace('"', '', $parts['1']);
        }
        return $rules;
    }
}