<?php

namespace App\blackjack;

class Card
{
    private string $suit;
    private string $value;


    public function __construct(string $suit, string $value)
    {
        $this->suit = $suit;
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getSuit(): string
    {
        return $this->suit;
    }

    public function getAsString(): string
    {
        return "[{$this->value} {$this->getSuitSymbol()}]";
    }

    private function getSuitSymbol(): string
    {
        return match ($this->suit) {
            'hearts' => '♥',
            'diamonds' => '♦',
            'clubs' => '♣',
            'spades' => '♠',
            default => ''
        };
    }

    public function getPointValue(): int
    {
        if ($this->value === 'A') {
            return 14; // Eller 1.
        }
        if (in_array($this->value, ['J', 'Q', 'K'])) {
            return 10;
        }
        return (int)$this->value;
    }
}
