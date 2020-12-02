<?php

namespace AdventOfCode\Console;

require_once __DIR__ . '/../../vendor/autoload.php';

if ($day = Utils::getInputOption("d")) {
    if (!$class = Utils::getClassByDayNumber($day)) {
        Utils::output('Invalid day value!');
        return;
    }
    $class->results();
} else {
    foreach (range(1, 25) as $dayNumber) {
        if ($class = Utils::getClassByDayNumber($dayNumber)) {
            $class->results();
        }
    }
}