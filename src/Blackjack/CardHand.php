<?php

namespace App\Blackjack;

/**
 * Class CardHand är en hand med spelkort.
 *
 * Hanterar en samling kort och beräknar det totala poängvärdet
 * med hantering av ess (som kan vara värda 1 eller 14 poäng)
 */
class CardHand
{
    /**
     * Lista av cardGraphic objekt i handen
     *
     * @var CardGraphic[]
     */
    private array $cards = [];

    /**
     * Lägg till ett kort till handen
     *
     * @param CardGraphic $card kortet som ska läggas till
     * @return void
     */
    public function addCard(CardGraphic $card): void
    {
        $this->cards[] = $card;
    }

    /**
     * Hämta alla kort i handen
     *
     * @return CardGraphic[] Lista av kort i handen
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * Beräkna det totala poängvärdet för handen
     *
     * Ess räknas  som 14 poäng, men ger automatiskt
     * till 1 poäng om det behövs för att undvika att gå över 21
     *
     * @return int Totalt poängvärde för handen
     */
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

    /**
     * Checkar om handen är en "bust"
     *
     * @return bool True om totalen är över 21, annars false
     */
    public function isBust(): bool
    {
        return $this->getTotal() > 21;
    }
}
