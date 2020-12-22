<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\Game\Player;

class Day22 extends BaseDay
{
    /** @var Player[] */
    private array $players = [];

    public function execute()
    {
        foreach ($this->getInputArray(PHP_EOL.PHP_EOL) as $definition) {
            $this->players[] = new Player($definition);
        }
        while (!$this->gameOver()) {
            $this->playRound();
        }
        $this->part1 = $this->getWinner()->getScore();
    }

    private function playRound()
    {
        $drawnCards = [];
        foreach ($this->players as $i => $player) {
            $drawnCards[$i] = $player->drawCard();
        }
        $winner = array_keys($drawnCards, max($drawnCards))[0];
        rsort($drawnCards);
        $this->players[$winner]->addCards($drawnCards);
    }

    private function gameOver() : bool
    {
        foreach ($this->players as $player) {
            if ($player->isLoser()) return true;
        }
        return false;
    }

    private function getWinner() : Player
    {
        foreach ($this->players as $player) {
            if (!$player->isLoser()) {
                return $player;
            }
        }
        throw new \Exception('Everyone is a loser');
    }
}