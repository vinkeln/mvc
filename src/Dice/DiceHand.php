<?php

namespace App\Dice;

use App\Dice\Dice;

class DiceHand
{
    private $hand = [];

    public function add(Dice $cards): void
    {
        $this->hand[] = $cards;
    }

    public function roll(): void
    {
        foreach ($this->hand as $cards) {
            $cards->roll();
        }
    }

    public function getNumberDices(): int
    {
        return count($this->hand);
    }

    public function getValues(): array
    {
        $values = [];
        foreach ($this->hand as $cards) {
            $values[] = $cards->getValue();
        }
        return $values;
    }

    public function getString(): array
    {
        $values = [];
        foreach ($this->hand as $cards) {
            $values[] = $cards->getAsString();
        }
        return $values;
    }
}
