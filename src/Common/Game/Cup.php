<?php

namespace AdventOfCode\Common\Game;

class Cup {
    private int $label;
    private Cup $next;

    public function __construct(int $label)
    {
        $this->label = $label;
    }

    public function pickNext() : Cup
    {
        $pickedCup = $this->getNext();
        $this->setNext($pickedCup->getNext());
        return $pickedCup;
    }

    public function placeNext(Cup $next)
    {
        $oldNext = $this->getNext();
        $this->setNext($next);
        $next->setNext($oldNext);
    }

    public function getLabel(): int
    {
        return $this->label;
    }

    public function getNext(): Cup
    {
        return $this->next;
    }

    public function setNext(Cup $next)
    {
        $this->next = $next;
    }
}