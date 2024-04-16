<?php

// src/Models/CardDeck.php

namespace App\Models;

class CardDeck
{
    private $cards = [];

    public function __construct()
    {
        $this->initializeDeck();
    }
    //lägger till korten i en array
    private function initializeDeck()
    {
        $suits = ['Hearts', 'Diamonds', 'Clubs', 'Spades'];
        $values = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'Jack', 'Queen', 'King', 'Ace'];

        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $this->cards[] = new CardGraphic($suit, $value);
            }
        }
    }
    //function to shuffle the deck
    public function shuffle()
{
    shuffle($this->cards);
}

    public function draw()
    {
        return array_shift($this->cards); // Return and remove the first card from the deck
    }

    /*public function drawMultiple($number)
    {
        $drawnCards = [];
        for ($i = 0; $i < $number; $i++) {
            if (!empty($this->cards)) {
                $drawnCards[] = $this->draw();
            } else {
                break;
            }
        }
        return $drawnCards;
    }*/

    public function getRemainingCards()
    {
        return count($this->cards);
    }


    public function getCards()
    {
        return $this->cards;
    }
}
