<?php

namespace App\Blackjack;

class CardGraphic extends Card
{
    private static array $suitToUnicode = [
        'spades' => ['🂡', '🂢', '🂣', '🂤', '🂥', '🂦', '🂧', '🂨', '🂩', '🂪', '🂫', '🂭', '🂮'],
        'hearts' => ['🂱', '🂲', '🂳', '🂴', '🂵', '🂶', '🂷', '🂸', '🂹', '🂺', '🂻', '🂽', '🂾'],
        'diamonds' => ['🃁', '🃂', '🃃', '🃄', '🃅', '🃆', '🃇', '🃈', '🃉', '🃊', '🃋', '🃍', '🃎'],
        'clubs' => ['🃑', '🃒', '🃓', '🃔', '🃕', '🃖', '🃗', '🃘', '🃙', '🃚', '🃛', '🃝', '🃞']
    ];

    private static array $valueToIndex = [
        'A' => 0, '2' => 1, '3' => 2, '4' => 3, '5' => 4,
        '6' => 5, '7' => 6, '8' => 7, '9' => 8, '10' => 9,
        'J' => 10, 'Q' => 11, 'K' => 12
    ];

    public function getAsString(): string
    {
        $suit = $this->getSuit();
        $value = $this->getValue();

        if (isset(self::$suitToUnicode[$suit]) && isset(self::$valueToIndex[$value])) {
            $index = self::$valueToIndex[$value];
            return self::$suitToUnicode[$suit][$index];
        }
        return parent::getAsString();
    }

    public function getColor(): string
    {
        $suit = $this->getSuit();
        return in_array($suit, ['hearts', 'diamonds']) ? 'red' : 'black';
    }
}
