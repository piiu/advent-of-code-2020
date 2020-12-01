<?php
require_once __DIR__ . '/vendor/autoload.php';

$options = getopt("d:");
$days = !empty($options) ? [$options['d']] : range(1, 25);

foreach ($days as $dayNumber) {
    $day = str_pad($dayNumber, 2, '0', STR_PAD_LEFT);
    $class = "AdventOfCode\Days\Day$day";
    if (class_exists($class)) {
        (new $class())->results();
    }
}