<?php

namespace AdventOfCode\Console;

use AdventOfCode\Common\BaseDay;

class Utils
{
    public static function outputArray(array $lines)
    {
        foreach ($lines as $line) {
            self::output($line);
        }
    }

    public static function output(string $message)
    {
        echo $message . PHP_EOL;
    }

    public static function getInputOption(string $option)
    {
        $options = getopt("$option:");
        if (empty($options)) {
            return null;
        }
        return $options[$option] ?? null;
    }

    public static function getClassByDayNumber(int $dayNumber) : ?BaseDay
    {
        $day = str_pad($dayNumber, 2, '0', STR_PAD_LEFT);
        $class = "AdventOfCode\Days\Day$day";
        if (class_exists($class)) {
            return new $class();
        }
        return null;
    }
}