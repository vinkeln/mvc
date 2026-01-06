<?php

namespace App\Blackjack;

class CardHand
{
    /** @var CardGraphic[] */
    private array $cards = [];

    public function addCard(CardGraphic $card): void
    {
        $this->cards[] = $card;
    }

    /** @return CardGraphic[] */
    public function getCards(): array
    {
        return $this->cards;
    }

    public function getTotal(): int
    {
        $total = 0;
        $aces = 0;

        foreach ($this->cards as $card) {
            $value = $card->getPointValue();
            if ($value === 14) { // Ess
                $aces++;
                $total += 14;
            } else {
                $total += $value;
            }
        }

        // Justera för ess (14 -> 1 om över 21)
        while ($total > 21 && $aces > 0) {
            $total -= 13; // 14 - 1 = 13
            $aces--;
        }

        return $total;
    }

    public function isBust(): bool
    {
        return $this->getTotal() > 21;
    }
}