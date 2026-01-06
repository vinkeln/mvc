<?php

namespace App\Blackjack;

class DeckOfCards
{
    private array $cards = [];

    public function __construct()
    {
       $this->initializeDeck();
    }
   private function initializeDeck(): void
    {
        $suits = ['spades', 'hearts', 'diamonds', 'clubs'];
        $values = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];

        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $this->cards[] = new CardGraphic($suit, $value);
            }
        }
    }

    public function shuffle(): void
    {
        shuffle($this->cards);
    }

    public function draw(): ?CardGraphic
    {
        return array_pop($this->cards);
    }

    public function getCount(): int
    {
        return count($this->cards);
    }

    /** @return CardGraphic[] */
    public function getCards(): array
    {
        return $this->cards;
    }
}