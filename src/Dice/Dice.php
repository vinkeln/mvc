<?php

namespace App\Dice;

/**
 * Class Dice representerar en tärning med ett värde mellan 1 och 6.
 */
class Dice
{
    private ?int $value = null; // null när tärningen inte kastats

    /**
     * Slå tärningen och returnera värdet.
     */
    public function roll(): int
    {
        $this->value = random_int(1, 6);
        return $this->value;
    }

    /**
     * Hämta det senaste värdet.
     */
    public function getValue(): ?int
    {
        return $this->value;
    }

    /**
     * Returnera tärningens värde som sträng.
     */
    public function getAsString(): string
    {
        return '[' . ($this->value ?? '-') . ']';
    }

    /**
     * Kontrollera om tärningen har kastats.
     */
    public function hasRolled(): bool
    {
        return $this->value !== null;
    }
}
