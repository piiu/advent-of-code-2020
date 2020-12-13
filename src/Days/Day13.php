<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Console\Utils;
use WolframAlpha\Engine;

class Day13 extends BaseDay
{
    public function execute()
    {
        list($earliest, $buses) = $this->getInputArray(PHP_EOL);
        $buses = explode(',', $buses);

        foreach ($buses as $bus) {
            if ($bus === 'x') {
                continue;
            }
            $minWaited = ceil($earliest / $bus) * $bus - $earliest;
            if (empty($min) || $min > $minWaited) {
                $min = $minWaited;
                $this->part1 = $bus * $minWaited;
            }
        }

        $equations = [];
        foreach ($buses as $offset => $bus) {
            if ($bus !== 'x') {
                $equations[] = "(x + $offset) mod $bus = 0";
            }
        }

        if ($waEngine = $this->getWaEngine()) {
            $result = $waEngine->process(implode(', ', $equations), [], ['plaintext']);
            $integerSolution = $result->pods->find('IntegerSolution')->subpods[0]->plaintext;
            preg_match('/x = \d* n \+ (\d*), n element Z/', $integerSolution, $matches);
            $this->part2 = $matches[1];
        }
    }

    private function getWaEngine(): ?Engine
    {
        $ini = parse_ini_file(__DIR__.'/../../config/app.ini');
        if ($id = $ini['wolfram_alpha_app_id'] ?? null) {
            return new Engine($id);
        }
        Utils::output('Missing Wolfram Alpha APP id from config');
        return null;
    }
}