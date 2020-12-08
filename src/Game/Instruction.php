<?php

namespace AdventOfCode\Game;

class Instruction
{
    const ACCUMULATOR = 'acc';
    const JUMP = 'jmp';
    const NO_OPERATION = 'nop';

    const PLUS = '+';
    const MINUS = '-';

    private string $type;
    private string $operator;
    private int $argument;

    public function __construct(string $type, string $operator, int $argument)
    {
        $this->type = $type;
        $this->operator = $operator;
        $this->argument = $argument;
    }

    public function apply(int &$location, int &$accumulator)
    {
        switch ($this->type) {
            case(self::ACCUMULATOR):
                $this->applyArgument($accumulator);
                $location++;
                return;
            case (self::JUMP):
                $this->applyArgument($location);
                return;
            case (self::NO_OPERATION):
                $location++;
                return;
        }
    }

    private function applyArgument(int &$target)
    {
        switch ($this->operator) {
            case (self::PLUS):
                $target += $this->argument;
                return;
            case (self::MINUS):
                $target -= $this->argument;
                return;
        }
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function setType(string $type)
    {
        $this->type = $type;
    }
}