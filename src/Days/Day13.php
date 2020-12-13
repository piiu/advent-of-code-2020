<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Console\Utils;
use WolframAlpha\Engine;

class Day13 extends BaseDay
{
    public function execute()
    {
        list($estimate, $buses) = $this->getInputArray(PHP_EOL);
        $buses = explode(',', $buses);

        $this->getNearest($estimate, $buses);

        if (!$waEngine = $this->getWaEngine()) {
            Utils::output('Missing Wolfram Alpha AppID, can not calculate part 2');
            return;
        }
        $this->getMinimalOffsetTime($buses, $waEngine);
    }

    public function getNearest(int $estimate, array $buses)
    {
        foreach ($buses as $bus) {
            if ($bus === 'x') {
                continue;
            }
            $minWaited = ceil($estimate / $bus) * $bus - $estimate;
            if (empty($min) || $min > $minWaited) {
                $min = $minWaited;
                $this->part1 = $bus * $minWaited;
            }
        }
    }

    public function getMinimalOffsetTime(array $buses, Engine $waEngine)
    {
        $equations = [];
        foreach ($buses as $offset => $bus) {
            if ($bus !== 'x') {
                $equations[] = "(x + $offset) mod $bus = 0";
            }
        }

        $result = $waEngine->process(implode(', ', $equations), [], ['plaintext']);
        $integerSolution = $result->pods->find('IntegerSolution')->subpods[0]->plaintext;
        preg_match('/x = \d* n \+ (\d*), n element Z/', $integerSolution, $matches);
        $this->part2 = $matches[1];
    }

    private function getWaEngine(): ?Engine
    {
        $ini = parse_ini_file(__DIR__.'/../../config/app.ini');
        if ($id = $ini['wolfram_alpha_app_id'] ?? null) {
            return new Engine($id);
        }
        return null;
    }
}