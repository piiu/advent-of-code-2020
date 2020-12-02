<?php

namespace AdventOfCode\Console;

require_once __DIR__ . '/../../vendor/autoload.php';

$allDays = range(1, 25);

$options = getopt("d:");
if (!empty($options)) {
    $day = $options['d'] ?? null;
    if (!$day || !in_array($day, $allDays)) {
        Utils::output('Invalid day value');
        die;
    }
}

$days = !empty($day) ? [$day] : $allDays;
foreach ($days as $dayNumber) {
    $day = str_pad($dayNumber, 2, '0', STR_PAD_LEFT);
    $class = "AdventOfCode\Days\Day$day";
    if (class_exists($class)) {
        (new $class())->results();
    }
}