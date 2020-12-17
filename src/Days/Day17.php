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

        $state1 = [$input];
        $state2 = [[$input]];

        for ($i = 0; $i < 6; $i++) {
            $this->part1 = $this->cycle($state1);
            $this->part2 = $this->cycle($state2);
        }
    }

    private function cycle(array &$state) : int
    {
        $fixedState = $state;
        $depths = $this->getMinMaxByDepth($fixedState);
        return $this->goDeeper($state, $fixedState, $depths);
    }

    private function goDeeper(array &$state, array $fixedState, array $depths, array $coordinates = [], $activeCount = 0) : int
    {
        if (!$depths) {
            if ($this->applyState($state, $fixedState, $coordinates) === self::ACTIVE) {
                $activeCount++;
            }
            return $activeCount;
        }
        list($min, $max) = array_shift($depths);
        for ($i = $min; $i <= $max; $i++) {
            $activeCount = $this->goDeeper($state, $fixedState, $depths, array_merge($coordinates, [$i]), $activeCount);
        }
        return $activeCount;
    }

    private function applyState(array &$state, array $fixedState, $coordinates) : string
    {
        $value = Arr::get($state, $coordinates) ?? self::INACTIVE;
        $neighbours = $this->getNeighboursCount($fixedState, $coordinates, $value === self::ACTIVE);
        $newState = $this->shouldBeActive($value, $neighbours) ? self::ACTIVE : self::INACTIVE;
        $state = Arr::set($state, $coordinates, $newState);
        return $newState;
    }

    private function shouldBeActive(string $value, int $neighbours) : bool
    {
        return (($value === self::ACTIVE && in_array($neighbours, [2, 3]))
            || ($value === self::INACTIVE && $neighbours === 3));
    }

    private function getMinMaxByDepth(array $state, array $depths = []) : array
    {
        $keys = array_keys($state);
        $depths[] = [min($keys) - 1, max($keys) + 1];
        if (is_array($state[0])) {
            return $this->getMinMaxByDepth($state[0], $depths);
        }
        return $depths;
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
}