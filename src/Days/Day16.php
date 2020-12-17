<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;

class Day16 extends BaseDay
{
    public function execute()
    {
        list($rules, $ticket, $otherTickets) = $this->getInputArray(PHP_EOL.PHP_EOL);

        $rules = explode(PHP_EOL, $rules);
        $validTickets = $this->getValidTickets($this->formatOtherTickets($otherTickets), $rules);

        $validityMap = $this->getRuleValidityMap($rules, $validTickets);
        $positions = $this->getRulePositions(array_keys($validityMap), $validityMap);
        $this->part2 = $this->getDepartureProduct(array_flip($positions), $this->formatTicket($ticket));
    }

    private function formatTicket(string $string) : array
    {
        $rows = explode(PHP_EOL, $string);
        return explode(',', $rows[1]);
    }

    private function formatOtherTickets(string $string) : array
    {
        $rows = explode(PHP_EOL, $string);
        array_shift($rows);
        return array_map(function($element) {
            return explode(',', $element);
        }, $rows);
    }

    private function ruleApplies(string $rule, int $value) : bool
    {
        preg_match('/[a-z ]*: (\d*)-(\d*) or (\d*)-(\d*)/', $rule, $matches);

        return ($value >= $matches[1] && $value <=$matches[2])
            || ($value >= $matches[3] && $value <=$matches[4]);
    }

    private function getValidTickets(array $tickets, array $rules) : array
    {
        $validTickets = [];
        $invalidValues = [];
        foreach ($tickets as $ticket) {
            $isValid = true;
            foreach ($ticket as $value) {
                foreach ($rules as $rule) {
                    if ($this->ruleApplies($rule, $value)) {
                        continue 2;
                    }
                }
                $invalidValues[] = $value;
                $isValid = false;
            }
            if ($isValid) {
                $validTickets[] = $ticket;
            }
        }
        $this->part1 = array_sum($invalidValues);
        return $validTickets;
    }

    private function getRulePositions(array $rulesLeft, array $validityMap, array $positions = []) : ?array
    {
        if (empty($rulesLeft)) {
            return $positions;
        }
        $currentRule = array_shift($rulesLeft);
        $canBeAppliedTo = array_diff($validityMap[$currentRule], array_values($positions));

        foreach ($canBeAppliedTo as $index) {
            $testPositions = array_merge($positions, [
                $currentRule => $index
            ]);
            if ($newPositions = $this->getRulePositions($rulesLeft, $validityMap, $testPositions)) {
                return $newPositions;
            }
        }
        return null;
    }

    private function getRuleValidityMap(array $rules, array $validTickets) : array
    {
        $validityMap = [];
        foreach ($rules as $rule) {
            for ($i=0; $i<20; $i++) {
                $canBeAppliedToAll = true;
                foreach ($validTickets as $ticket) {
                    if (!$this->ruleApplies($rule, $ticket[$i])) {
                        $canBeAppliedToAll = false;
                        break;
                    }
                }
                if ($canBeAppliedToAll) {
                    $validityMap[$rule][] = $i;
                }
            }
        }

        asort($validityMap);
        return $validityMap;
    }

    private function getDepartureProduct(array $positions, array $ticket) : int
    {
        $departureValues = [];
        foreach ($ticket as $index => $value) {
            if (explode(' ', $positions[$index])[0] === 'departure') {
                $departureValues[] = $value;
            }
        }
        return array_product($departureValues);
    }
}