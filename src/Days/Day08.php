<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\Game\Console;
use AdventOfCode\Common\Game\Instruction;

class Day08 extends BaseDay
{
    public function execute()
    {
        $console = new Console($this->getInputArray());
        while (!$console->instructionRunBefore()) {
            $console->runNextInstruction();
        }
        $this->part1 = $console->getAccumulator();

        $console->reset();

        foreach ($console->getCode() as $index => $instruction) {
            if ($instruction->getType() === Instruction::ACCUMULATOR) {
                continue;
            }
            $testConsole = clone($console);
            $testInstruction = clone($instruction);
            if ($instruction->getType() === Instruction::JUMP) {
                $testInstruction->setType(Instruction::NO_OPERATION);
                $testConsole->setInstruction($index, $testInstruction);
            }
            if ($instruction->getType() === Instruction::NO_OPERATION) {
                $testInstruction->setType(Instruction::JUMP);
                $testConsole->setInstruction($index, $testInstruction);
            }
            while (!$testConsole->instructionRunBefore() && !$testConsole->isFinished()) {
                $testConsole->runNextInstruction();
            }
            if ($testConsole->isFinished()) {
                $this->part2 = $testConsole->getAccumulator();
                break;
            }
        }

    }
}