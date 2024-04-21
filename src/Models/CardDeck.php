<?php

// src/Models/CardDeck.php

namespace App\Models;

class CardDeck 
{

    private $cards = [];
    private $suit;

    public function __construct()
    {
        $this->initializeDeck();
        session_start();
    }
    //lägger till korten i en array
    private function initializeDeck()
    {
        $suits = ['Hearts', 'Diamonds', 'Clubs', 'Spades'];
        $values = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'Jack', 'Queen', 'King', 'Ace'];

        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $this->cards[] = new Card($suit, $value);
            }
        }
        $this->sortdeck();
    }
    public function sortdeck()
    {
        usort($this->cards, function ($card1, $card2) {
            if ($card1->getSuit() === $card2->getSuit()) {
                return $card1->getValue() <=> $card2->getValue();
            }
            return $card1->getSuit() <=> $card2->getSuit();
        });
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

    public function drawMultiple($number)
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
    }
    //card/deck 
    public function getDeck() {
        return $this->cards;
    }

    public function getRemainingCards()
    {
        return count($this->cards);
    }


    public function getCardsInfo()
    {
        return $this->cards;
    }

        public function getColor(): string
    {
        if ($this->suit === 'Hearts' || $this->suit === 'Diamonds') {
            return 'red';
        } else {
            return 'black';
        }
    }
    //api/deck??
    public function getCards()
    {
        return array_map(function ($card) {
            return [
                'suit' => $card->getSuit(),
                'value' => $card->getValue()
            ];
        }, $this->cards);
    }
}