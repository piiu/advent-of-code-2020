<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\Game\Player;

class Day22 extends BaseDay
{
    public function execute()
    {
        $players = $this->getPlayers();
        $winner = $this->play($players);
        $this->part1 = $players[$winner]->getScore();

        $players = $this->getPlayers();
        $winner = $this->playRecursive($players);
        $this->part2 = $players[$winner]->getScore();
    }

    private function play(array $players) : int
    {
        while (!$this->gameOver($players)) {
            $drawnCards = [];
            foreach ($players as $i => $player) {
                $drawnCards[$i] = $player->drawCard();
            }
            $winnerCard = max($drawnCards);
            $this->redistributeCards($players, $winnerCard, $drawnCards);
        }
        return $this->getWinner($players);
    }

    private function playRecursive(array $players) : int
    {
        while (!$this->gameOver($players)) {
            $this->playRound($players);
            if ($this->thisHasHappenedBefore($players)) {
                return 0;
            }
        }
        return $this->getWinner($players);
    }

    private function playRound(array $players)
    {
        $drawnCards = [];
        $isRecursive = true;
        foreach ($players as $i => $player) {
            $card = $player->drawCard();
            if ($card > $player->cardsLeft()) {
                $isRecursive = false;
            }
            $drawnCards[$i] = $card;
        }
        if ($isRecursive) {
            $newPlayers = [];
            foreach ($players as $i => $player) {
                $newPlayers[$i] = $player->getCopy($drawnCards[$i]);
            }
            $winner = $this->playRecursive($newPlayers);
            $winningCard = $drawnCards[$winner];
        } else {
            $winningCard = max($drawnCards);
        }
        $this->redistributeCards($players, $winningCard, $drawnCards);
    }

    private function redistributeCards(array $players, int $winningCard, array $drawnCards)
    {
        $winner = array_keys($drawnCards, $winningCard)[0];
        unset($drawnCards[$winner]);
        $players[$winner]->addCards(array_merge([$winningCard], $drawnCards));
    }

    private function thisHasHappenedBefore(array $players) : bool
    {
        foreach ($players as $i => $player) {
            if ($player->hasHappenedBefore()) {
                return true;
            }
        }
        return false;
    }

    private function gameOver(array $players) : bool
    {
        foreach ($players as $player) {
            if ($player->isLoser()) return true;
        }
        return false;
    }

    private function getWinner(array $players) : int
    {
        foreach ($players as $i => $player) {
            if (!$player->isLoser()) {
                return $i;
            }
        }
        throw new \Exception('Everyone is a loser');
    }

    private function getPlayers() : array
    {
        $players = [];
        foreach ($this->getInputArray(PHP_EOL.PHP_EOL) as $definition) {
            $rows = explode(PHP_EOL, $definition);
            array_shift($rows);
            $players[] = new Player($rows);
        }
        return $players;
    }
}