<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;

class Day17 extends BaseDay
{
    const ACTIVE = '#';
    const INACTIVE = '.';

    private array $state;

    public function execute()
    {
        $input = array_map(function ($row) {
            return str_split($row);
        }, $this->getInputArray(PHP_EOL));

        $this->state = [[$input]];

        for ($i=0; $i<6; $i++) {
            $this->expand();
            $currentState = $this->state;
            foreach ($currentState as $w => $dimension) {
                foreach ($dimension as $z => $layer) {
                    foreach ($layer as $y => $row) {
                        foreach ($row as $x => $value) {
                            $neighbours = $this->getNumberOfNeighbours($currentState, $x, $y, $z, $w);
                            if ($value === self::ACTIVE && !in_array($neighbours, [2, 3])) {
                                $this->state[$w][$z][$y][$x] = self::INACTIVE;
                            }
                            if ($value === self::INACTIVE && $neighbours === 3) {
                                $this->state[$w][$z][$y][$x] = self::ACTIVE;
                            }
                        }
                    }
                }
            }
        }

        foreach ($this->state as $w => $dimension) {
            foreach ($dimension as $z => $layer) {
                foreach ($layer as $y => $row) {
                    foreach ($row as $x => $value) {
                        if ($value === self::ACTIVE) {
                            $this->part1++;
                        }
                    }
                }
            }
        }
    }

    private function getNumberOfNeighbours(array $state, $x, $y, $z, $w) : int
    {
        $number = 0;
        foreach (range($x-1, $x+1) as $cx) {
            foreach (range($y-1, $y+1) as $cy) {
                foreach (range($z-1, $z+1) as $cz) {
                    foreach (range($w-1, $w+1) as $cw) {
                        if ($cx === $x && $cy === $y && $cz === $z && $cw === $w) {
                            continue;
                        }
                        $value = $state[$cw][$cz][$cy][$cx] ?? null;
                        if ($value && $value === self::ACTIVE) {
                            $number++;
                        }
                    }
                }
            }
        }

        return $number;
    }

    private function expand() {
        $wKeys = array_keys($this->state);
        foreach (range(min($wKeys)-1, max($wKeys)+1) as $w) {
            $zKeys = array_keys($this->state[0]);
            foreach (range(min($zKeys) - 1, max($zKeys) + 1) as $z) {
                $yKeys = array_keys($this->state[0][0]);
                foreach (range(min($yKeys) - 1, max($yKeys) + 1) as $y) {
                    $xKeys = array_keys($this->state[0][0][0]);
                    foreach (range(min($xKeys) - 1, max($xKeys) + 1) as $x) {
                        $this->state[$w][$z][$y][$x] = $this->state[$w][$z][$y][$x] ?? self::INACTIVE;
                    }
                }
            }
        }
    }
}