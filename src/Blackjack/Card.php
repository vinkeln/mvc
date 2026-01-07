<?php

namespace App\Blackjack;

/**
 * Class Card representerar ett spelkort med svit och värde.
 */
class Card
{
    /** @var string  Svit */
    private string $suit;
    /** @var string  Värde */
    private string $value;

    /**
     * Konstruktor för att skapa ett kort med given svit och värde
     *
     * @param string $suit Svit på kortet
     * @param string $value  Värde på kortet
     */
    public function __construct(string $suit, string $value)
    {
        $this->suit = $suit;
        $this->value = $value;
    }

    /**
     * Hämta kortets värde.
     *
     * @return string Kortets värde.
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Hämta kortets svit.
     *
     * @return string Kortets svit.
     */
    public function getSuit(): string
    {
        return $this->suit;
    }

    /**
     * Hämta en sträng av kortet
     *
     * @return string Sträng av kortet
     */
    public function getAsString(): string
    {
        return "[{$this->value} {$this->getSuitSymbol()}]";
    }

    /**
     * Hämta symbolen för sviten (unicode = hearts, diamonds, clubs, spades)
     *
     * @return string Unicode symbol för sviten
     */
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

    /**
     * Hämta kortets poängvärde
     *
     * @return int Kortets poängvärde
     */
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
