<?php

// Test fil mot src/Blackjack/Card.php
namespace App\Tests\Blackjack;

use App\Blackjack\Card;
use PHPUnit\Framework\TestCase;

class CardTest extends TestCase
{
    // Testar att skapandet av ett kort fungerar korrekt
    public function testCreateCard(): void
    {
        $card = new Card('hearts', 'A');
        
        $this->assertInstanceOf(Card::class, $card);
        $this->assertEquals('hearts', $card->getSuit());
        $this->assertEquals('A', $card->getValue());
    }

    public function testGetPointAce(): void
    {
        $card = new Card('hearts', 'A');
        $this->assertEquals(14, $card->getPointValue());
    }

    public function testGetPointFaceCard(): void
    {
        $jack = new Card('hearts', 'J');
        $queen = new Card('hearts', 'Q');
        $king = new Card('hearts', 'K');
        
        $this->assertEquals(10, $jack->getPointValue());
        $this->assertEquals(10, $queen->getPointValue());
        $this->assertEquals(10, $king->getPointValue());
    }


    public function testGetPointValueNumber(): void
    {
        $card = new Card('hearts', '5');
        $this->assertEquals(5, $card->getPointValue());
    }

    public function testGetAsString(): void
    {
        $card = new Card('hearts', 'A');
        $result = $card->getAsString();


        $this->assertStringContainsString('A', $result);
        $this->assertStringContainsString('♥', $result);
    }

    public function testGetSuitSymbol(): void
    {
        $hearts = new Card('hearts', 'A');
        $diamonds = new Card('diamonds', 'A');
        $clubs = new Card('clubs', 'A');
        $spades = new Card('spades', 'A');


        $this->assertStringContainsString('♥', $hearts->getAsString());
        $this->assertStringContainsString('♦', $diamonds->getAsString());
        $this->assertStringContainsString('♣', $clubs->getAsString());
        $this->assertStringContainsString('♠', $spades->getAsString());
    }
}
