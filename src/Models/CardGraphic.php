<?php

namespace App\Models;

//skapar en ny class och ärver från Cards-klassen
class CardGraphic extends Card
{ //skapar en privat variabel som innehåller en array med kort
    //kan bara nås inifrån CardGraphic-klassen
    private $representation = [
        '🂡', '🂢', '🂣', '🂤', '🂥', '🂦', '🂧', '🂨',
        '🂩', '🂪', '🂫', '🂬', '🂭', '🂮', '🂱', '🂲',
        '🂳', '🂴', '🂵', '🂶', '🂷', '🂸', '🂹', '🂺',
        '🂻', '🂼', '🂽', '🂾', '🃁', '🃂', '🃃', '🃄',
        '🃅', '🃆', '🃇', '🃈', '🃉', '🃊', '🃋', '🃌',
        '🃍', '🃎', '🃑', '🃒', '🃓', '🃔', '🃕', '🃖',
        '🃗', '🃘', '🃙', '🃚', '🃛', '🃜', '🃝', '🃞'
    ];

    private $cardColors = [];

    //konstruktor för CardGraphic-klassen
    public function __construct()
    {
        parent::__construct();
        $this->setCardColors();
    }


    public function shuffle()
    {
        return shuffle($this->representation);
    }

    public function drawCard($number = 1)
    {
        if (!is_array($this->representation)) {
            throw new \Exception("Expected representation to be an array.");
        }
        $cardsDrawn = array_splice($this->representation, 0, $number);
        return $cardsDrawn;
    }

    public function shuffleCards()
    {
        shuffle($this->representation);
    }


    /*public function remainingCards()
    {
        return count($this->representation);

    }*/

    public function getCards(): array
    {
        return $this->representation;
    }


    // retunerar en strängrepresentation av kortet i deck/routern
    public function getAsString(): string
    {
        return $this->representation[$this->value - 1];
    }

    //flytta till CardDeck
    public function getDeck()
    {
        return $this->representation;
    }

    public function setDeck($deck)
    {
        $this->representation = $deck;
    }

    public function setCardColors()
    {
        $redSuits = ['🂱', '🂲', '🂳', '🂴', '🂵', '🂶', '🂷', '🂸', '🂹', '🂺', '🂻', '🂼', '🂽', '🂾','🃁', '🃂', '🃃', '🃄', '🃅', '🃆', '🃇', '🃈', '🃉', '🃊', '🃋', '🃌', '🃍', '🃎'];
        $blackSuits = ['🃑', '🃒', '🃓', '🃔', '🃕', '🃖', '🃗', '🃘', '🃙', '🃚', '🃛', '🃜', '🃝', '🃞','🂡', '🂢', '🂣', '🂤', '🂥', '🂦', '🂧', '🂨', '🂩', '🂪', '🂫', '🂬', '🂭', '🂮'];

        foreach ($this->representation as $card) {
            if (in_array($card, $redSuits)) {
                $this->cardColors[$card] = 'red';
            } else {
                $this->cardColors[$card] = 'black';
            }
        }
    }

    public function getCardColors(): array
    {
        return $this->cardColors;
    }
}
