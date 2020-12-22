<?php

namespace AdventOfCode\Common\Game;

class Player
{
    private array $deck;
    private array $previousDecks = [];

    public function __construct(array $deck)
    {
        $this->deck = $deck;
    }

    public function isLoser() : bool
    {
        return empty($this->deck);
    }

    public function drawCard() : int
    {
        $this->previousDecks[] = $this->deck;
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

    public function hasHappenedBefore() : bool
    {
        foreach ($this->previousDecks as $previousDeck) {
            if ($previousDeck === $this->deck) {
                return true;
            }
        }
        return false;
    }

    public function cardsLeft() : int
    {
        return count($this->deck);
    }

    public function getCopy(int $deckSize) : self
    {
        $newDeck = array_slice($this->deck, 0, $deckSize);
        return new Player($newDeck);
    }
}