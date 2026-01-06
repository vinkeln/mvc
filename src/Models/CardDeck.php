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
        $suits = ['♠', '♣', '♥', '♦'];
        $values = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];
        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $color = ($suit === '♥' || $suit === '♦') ? 'red' : 'black';
                $this->cards[] = (object) ['value' => $value, 'suit' => $suit, 'color' => $color];
            }
        }
        return $this->cards;
    }

    public function deteminateColor($suit)
    {
        if ($suit === 'Hearts' || $suit === 'Diamonds') {
            return 'red';
        } else {
            return 'black';
        }
    }
    public function sortdeck()
    {
        usort($this->cards, function ($card1, $card2) { // sortera cards arrayen
            if ($card1->getSuit() === $card2->getSuit()) {
                // kollar om suit har samma färg, om de har jämförs deras värde
                return $card1->getValue() <=> $card2->getValue(); //
            } // Om färgerna på de två korten inte är lika, jämför deras färger
            return $card1->getSuit() <=> $card2->getSuit();
        });
    }
    //function to shuffle the deck
    public function shuffleDeck()
    {
        return shuffle($this->cards);
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
    public function getDeck()
    {
        return $this->cards;
    }

    public function getCardString($card)
    {
        return "{$card->value}{$card->suit}"; 
    }


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
