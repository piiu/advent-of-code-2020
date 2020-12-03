<?php

namespace AdventOfCode\Console;

require_once __DIR__ . '/../../vendor/autoload.php';

if (!$day = Utils::getInputOption("d")) {
    Utils::output('Please input day number!');
    return;
}
if (!$class = Utils::getClassByDayNumber($day)) {
    Utils::output('Invalid day value!');
    return;
}
$class->results();
