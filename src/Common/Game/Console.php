<?php

namespace AdventOfCode\Common\Game;

class Console {
    /** @var Instruction[] */
    private array $code = [];
    private int $accumulator = 0;
    private int $location = 0;
    private array $history = [];

    public function __construct(array $lines)
    {
        foreach ($lines as $line) {
            preg_match('/([a-z]{3}) ([+|-])([0-9]*)/', $line, $matches);
            $this->code[] = new Instruction($matches[1], $matches[2], $matches[3]);
        }
    }

    public function runNextInstruction()
    {
        $this->history[] = $this->location;
        $this->code[$this->location]->apply($this->location, $this->accumulator);
        $this->accumulator;
    }

    public function instructionRunBefore() : bool
    {
        return in_array($this->location, $this->history);
    }

    public function reset()
    {
        $this->location = 0;
        $this->accumulator = 0;
        $this->history = [];
    }

    public function isFinished() : bool
    {
        return $this->location === count($this->code);
    }

    public function setInstruction(int $index, Instruction $instruction)
    {
        $this->code[$index] = $instruction;
    }

    public function getAccumulator(): int
    {
        return $this->accumulator;
    }

    public function getCode(): array
    {
        return $this->code;
    }
}

