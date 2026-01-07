<?php

// Testar mot filen src/Blackjack/CardGraphic.php
namespace App\Tests\Blackjack;

use App\Blackjack\CardGraphic;
use PHPUnit\Framework\TestCase;

class CardGraphicTest extends TestCase
{
    public function testCreateCardGraphic(): void
    {
        $card = new CardGraphic('hearts', 'A');
        
        $this->assertInstanceOf(CardGraphic::class, $card);
        $this->assertEquals('hearts', $card->getSuit());
        $this->assertEquals('A', $card->getValue());
    }

    public function testGetAsStringReturns(): void
    {
        $card = new CardGraphic('spades', 'A');
        $result = $card->getAsString();
        
        // Spader ess
        $this->assertEquals('🂡', $result);
    }

    public function testColorRed(): void
    {
        $hearts = new CardGraphic('hearts', 'A');
        $diamonds = new CardGraphic('diamonds', 'K');

        $this->assertEquals('red', $hearts->getColor());
        $this->assertEquals('red', $diamonds->getColor());
    }

    public function testColorBlack(): void
    {
        $spades = new CardGraphic('spades', 'A');
        $clubs = new CardGraphic('clubs', 'K');

        $this->assertEquals('black', $spades->getColor());
        $this->assertEquals('black', $clubs->getColor());
    }

    public function testAllSuits(): void
    {
        $spades = new CardGraphic('spades', '2');
        $hearts = new CardGraphic('hearts', '2');
        $diamonds = new CardGraphic('diamonds', '2');
        $clubs = new CardGraphic('clubs', '2');

        $this->assertEquals('🂢', $spades->getAsString());
        $this->assertEquals('🂲', $hearts->getAsString());
        $this->assertEquals('🃂', $diamonds->getAsString());
        $this->assertEquals('🃒', $clubs->getAsString());
    }
}
