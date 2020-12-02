<?php

namespace AdventOfCode\Common;

class Utils
{
    public static function output(array $lines)
    {
        foreach ($lines as $line) {
            echo $line . PHP_EOL;
        }
    }
}