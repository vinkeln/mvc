<?php

// Testar mot filen src/Blackjack/DeckOfCards.php
namespace App\Tests\Blackjack;

use App\Blackjack\DeckOfCards;
use App\Blackjack\CardGraphic;
use PHPUnit\Framework\TestCase;

class DeckOfCardsTest extends TestCase
{
    public function testCreateDeck(): void
    {
        $deck = new DeckOfCards();

        $this->assertInstanceOf(DeckOfCards::class, $deck);
        $this->assertEquals(52, $deck->getCount());
    }

    public function testGetCards(): void
    {
        $deck = new DeckOfCards();
        $cards = $deck->getCards();

        $this->assertIsArray($cards);
        $this->assertCount(52, $cards);
        $this->assertInstanceOf(CardGraphic::class, $cards[0]);
    }

    public function testShuffle(): void
    {
        $deck1 = new DeckOfCards();
        $deck2 = new DeckOfCards();
        
        $cards1Before = $deck1->getCards();
        $deck1->shuffle();
        $cards1After = $deck1->getCards();

        $this->assertEquals(52, $deck1->getCount());

        $this->assertNotEquals(
            $cards1Before[0]->getAsString(),
            $cards1After[0]->getAsString()
        );
    }

    public function testDraw(): void
    {
        $deck = new DeckOfCards();
        $card = $deck->draw();
        
        $this->assertInstanceOf(CardGraphic::class, $card);
        $this->assertEquals(51, $deck->getCount());
    }

    public function testDrawMultiple(): void
    {
        $deck = new DeckOfCards();
        for ($i = 0; $i < 10; $i++) {
            $deck->draw();
        }

        $this->assertEquals(42, $deck->getCount());
    }

    public function testDrawAll(): void
    {
        $deck = new DeckOfCards();
        
        for ($i = 0; $i < 52; $i++) {
            $card = $deck->draw();
            $this->assertInstanceOf(CardGraphic::class, $card);
        }
        
        $this->assertEquals(0, $deck->getCount());
    }

    public function testDrawFromEmptyDeck(): void
    {
        $deck = new DeckOfCards();

        // Drar alla kort
        for ($i = 0; $i < 52; $i++) {
            $deck->draw();
        }

        // Försöker att dra från tom kortlek
        $card = $deck->draw();
        $this->assertNull($card);
    }
}
