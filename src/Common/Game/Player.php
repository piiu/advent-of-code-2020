<?php

namespace AdventOfCode\Common\Game;

class Player
{
    private string $name;
    private array $deck;

    public function __construct(string $definition)
    {
        $rows = explode(PHP_EOL, $definition);
        $this->name = rtrim(array_shift($rows),':');
        $this->deck = $rows;
    }

    public function isLoser() : bool
    {
        return empty($this->deck);
    }

    public function drawCard() : int
    {
        return array_shift($this->deck);
    }

    public function addCards(array $cards)
    {
        foreach ($cards as $card) {
            $this->deck[] = $card;
        }
    }

    public function getScore($score = 0) : int
    {
        for ($i=count($this->deck); $i>0; $i--) {
            $score += $i * $this->deck[count($this->deck) - $i];
        }
        return $score;
    }
}