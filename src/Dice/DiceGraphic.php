<?php

namespace App\Dice;

class DiceGraphic extends Dice
{
    /**
     * Grafisk representation av tärningen (Unicode).
     */
    private $representation = [
        '⚀',
        '⚁',
        '⚂',
        '⚃',
        '⚄',
        '⚅',
    ];

    /**
     * Returnerar tärningens grafiska symbol.
     */
    public function getAsString(): string
    {
        $value = $this->getValue();

        // Om tärningen inte kastats än
        if ($value === null) {
            return '-';
        }

        return $this->representation[$value - 1];
    }

    /**
     * Returnerar tärningens grafiska färg. (valfritt)
     */
    public function getColor(): string
    {
        $value = $this->getValue();
        if ($value === null) {
            return 'none';
        }

        // Röd för 1 och 6 exempelvis, annars svart
        return ($value === 1 || $value === 6) ? 'red' : 'black';
    }
}
