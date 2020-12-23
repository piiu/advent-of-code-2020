<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\Game\Cup;

class Day23 extends BaseDay
{
    /** @var Cup[] */
    private array $cups = [];
    private Cup $currentCup;
    private int $cupCount;

    public function execute()
    {
        $this->loadCups();
        $this->cycle(100);
        $this->part1 = $this->getResult1();

        $this->loadCups(1000000);
        $this->cycle(10000000);
        $this->part2 = $this->getResult2();
    }

    private function cycle(int $times)
    {
        for ($i=1; $i<=$times; $i++) {
            $pickedCups = $this->pick(3);
            $destination = $this->getDestination($pickedCups);
            $this->placeCups($destination, $pickedCups);
            $this->currentCup = $this->currentCup->getNext();
        }
    }

    private function pick($amount) : array
    {
        $pickedCups = [];
        for ($i=0; $i<$amount; $i++) {
            $picked = $this->currentCup->pickNext();
            $pickedCups[$picked->getLabel()] = $picked;
        }
        return $pickedCups;
    }

    private function getDestination(array $pickedCups) : Cup
    {
        $destination = $this->currentCup->getLabel();
        do {
            $destination = $destination - 1;
            if (!isset($this->cups[$destination])) {
                $destination = $this->cupCount;
            }
        } while (in_array($destination, array_keys($pickedCups)));
        return $this->cups[$destination];
    }

    private function placeCups(Cup $destination, array $pickedCups)
    {
        foreach ($pickedCups as $cup) {
            $destination->placeNext($cup);
            $destination = $cup;
        }
    }

    private function getResult1() : string
    {
        $string = '';
        $nextCup = $this->cups[1];
        do {
            $nextCup = $nextCup->getNext();
            $string .= $nextCup->getLabel();
        } while ($nextCup->getNext()->getLabel() !== 1);
        return $string;
    }

    private function getResult2() : int
    {
        $one = $this->cups[1];
        $star1 = $one->getNext();
        $star2 = $star1->getNext();
        return $star1->getLabel() * $star2->getLabel();
    }

    private function loadCups(int $total = null)
    {
        $this->cups = [];
        $labels = $this->getInputNumbers();
        if ($total) {
            $labels = array_merge($labels, range(max($labels) + 1, $total));
        }
        $this->cupCount = $total ?? count($labels);
        $previousCup = null;
        foreach ($labels as $label) {
            $cup = new Cup($label);
            $this->currentCup = $this->currentCup ?? $cup;
            $this->cups[$label] = $cup;
            if ($previousCup) {
                $previousCup->setNext($cup);
            }
            $previousCup = $cup;
        }
        $previousCup->setNext($this->currentCup);
    }
}