<?php

namespace AdventOfCode\Console;

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
}