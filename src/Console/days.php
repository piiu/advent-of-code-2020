<?php

namespace AdventOfCode\Console;

require_once __DIR__ . '/../../vendor/autoload.php';

foreach (range(1, 25) as $dayNumber) {
    if ($class = Utils::getClassByDayNumber($dayNumber)) {
        $class->results();
    }
}