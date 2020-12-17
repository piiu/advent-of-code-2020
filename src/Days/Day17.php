<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;
use Minwork\Helper\Arr;

class Day17 extends BaseDay
{
    const ACTIVE = '#';
    const INACTIVE = '.';

    public function execute()
    {
        $input = array_map(function ($row) {
            return str_split($row);
        }, $this->getInputArray(PHP_EOL));

        $state1 = [[$input]];
        $state2 = [[$input]];

        for ($i = 0; $i < 6; $i++) {
            $this->expand($state1);
            $this->part1 = $this->cycle($state1);
            $this->expand($state2, true);
            $this->part2 = $this->cycle($state2);
        }
    }

    private function cycle(array &$state, int $activeCount = 0) : int
    {
        $fixedState = $state;
        foreach ($fixedState as $w => $dimension) {
            foreach ($dimension as $z => $layer) {
                foreach ($layer as $y => $row) {
                    foreach ($row as $x => $value) {
                        $coordinates = [$w, $z, $y, $x];
                        $neighbours = $this->getNeighboursCount($fixedState, $coordinates, $value === self::ACTIVE);
                        $newState = $this->shouldBeActive($value, $neighbours) ? self::ACTIVE : self::INACTIVE;
                        $state = Arr::set($state, $coordinates, $newState);

                        if ($newState === self::ACTIVE) {
                            $activeCount++;
                        }
                    }
                }
            }
        }

        return $activeCount;
    }

    private function getNeighboursCount(array $state, array $coordinates, bool $self = true, int $number = 0) : int
    {
        $coordinate = array_shift($coordinates);
        foreach (range($coordinate-1, $coordinate+1) as $i) {
            if (empty($state[$i])) {
                continue;
            }
            if (is_array($state[$i])) {
                $number = $this->getNeighboursCount($state[$i], $coordinates, false, $number);
            }
            if ($state[$i] === self::ACTIVE) {
                $number++;
            }
        }
        return $self ? $number - 1 : $number;
    }

    private function shouldBeActive(string $value, int $neighbours) : bool
    {
        return (($value === self::ACTIVE && in_array($neighbours, [2, 3])) || ($value === self::INACTIVE && $neighbours === 3));
    }

    private function expand(array &$state, bool $is4d = false)
    {
        $wKeys = array_keys($state);
        $wRange = $is4d ? range(min($wKeys)-1, max($wKeys)+1) : [0];
        foreach ($wRange as $w) {
            $zKeys = array_keys($state[0]);
            foreach (range(min($zKeys) - 1, max($zKeys) + 1) as $z) {
                $yKeys = array_keys($state[0][0]);
                foreach (range(min($yKeys) - 1, max($yKeys) + 1) as $y) {
                    $xKeys = array_keys($state[0][0][0]);
                    foreach (range(min($xKeys) - 1, max($xKeys) + 1) as $x) {
                        $state[$w][$z][$y][$x] = $state[$w][$z][$y][$x] ?? self::INACTIVE;
                    }
                }
            }
        }
    }
}