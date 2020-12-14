<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;

class Day14 extends BaseDay
{
    private array $memory = [];
    private array $mask = [];

    public function execute()
    {
        $lines = $this->getInputArray(PHP_EOL);

        $this->run($lines, 1);
        $this->part1 = array_sum($this->memory);

        $this->resetMemory();

        $this->run($lines, 2);
        $this->part2 = array_sum($this->memory);
    }

    private function run(array $lines, int $version)
    {
        foreach ($lines as $line) {
            list($key, $value) = explode(' = ', $line);
            if ($key === 'mask') {
                $this->mask = str_split($value);
                continue;
            }
            $memoryKey = $this->getKey($key);
            $this->{'writeV'.$version}($memoryKey, $value);
        }
    }

    private function writeV1($key, $value)
    {
        $this->memory[$key] = bindec($this->applyMask($value, 'X'));
    }

    private function writeV2($key, $value)
    {
        $keyMask = $this->applyMask($key, '0');
        $floatCount = substr_count($keyMask, 'X');
        $binLength = pow(2, $floatCount) - 1;
        foreach (range(0, $binLength) as $replaceNumber) {
            $replacements = str_split($this->getBinary($replaceNumber, $floatCount));
            $memoryIndex = bindec($this->replaceFloats($keyMask, $replacements));
            $this->memory[$memoryIndex] = $value;
        }
    }

    private function applyMask(int $value, string $ignoredValue)
    {
        $binary = $this->getBinary($value, count($this->mask));
        foreach ($this->mask as $index => $replace) {
            if ($replace === $ignoredValue) {
                continue;
            }
            $binary = substr_replace($binary, $replace, $index, 1);
        }

        return $binary;
    }

    private function replaceFloats(string $value, array $replacements) : string
    {
        $replacementIndex = 0;
        foreach (str_split($value) as $index => $char) {
            if ($char !== 'X') {
                continue;
            }
            $value = substr_replace($value, $replacements[$replacementIndex], $index, 1);
            $replacementIndex++;
        }
        return $value;
    }

    private function getBinary(int $value, int $length) : string
    {
        return str_pad(decbin($value), $length, '0', STR_PAD_LEFT);
    }

    private function getKey(string $string)
    {
        preg_match('/mem\[(\d*)]/', $string, $matches);
        return $matches[1];
    }

    private function resetMemory()
    {
        $this->memory = [];
    }
}