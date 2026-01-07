<?php

// Testar mot filen src/Blackjack/CardHand.php
namespace App\Tests\Blackjack;

use App\Blackjack\CardHand;
use App\Blackjack\CardGraphic;
use PHPUnit\Framework\TestCase;

class CardHandTest extends TestCase
{
    public function testCreateEmptyHand(): void
    {
        $hand = new CardHand();

        $this->assertInstanceOf(CardHand::class, $hand);
        $this->assertCount(0, $hand->getCards());
        $this->assertEquals(0, $hand->getTotal());
    }

    public function testAddCard(): void
    {
        $hand = new CardHand();
        $card = new CardGraphic('hearts', '5');
        $hand->addCard($card);
        
        $this->assertCount(1, $hand->getCards());
        $this->assertEquals(5, $hand->getTotal());
    }

    public function testGetTotal(): void
    {
        $hand = new CardHand();
        $hand->addCard(new CardGraphic('hearts', '5'));
        $hand->addCard(new CardGraphic('clubs', '10'));

        $this->assertEquals(15, $hand->getTotal());
    }

    public function testGetTotalWithFaceCards(): void
    {
        $hand = new CardHand();
        $hand->addCard(new CardGraphic('hearts', 'K'));
        $hand->addCard(new CardGraphic('clubs', 'Q'));

        $this->assertEquals(20, $hand->getTotal());
    }

    public function testGetTotalWithAceAs14(): void
    {
        $hand = new CardHand();
        $hand->addCard(new CardGraphic('hearts', 'A'));
        $hand->addCard(new CardGraphic('clubs', '5'));

        $this->assertEquals(19, $hand->getTotal());
    }

    public function testGetTotalWithAceAs1(): void
    {
        $hand = new CardHand();
        $hand->addCard(new CardGraphic('hearts', 'A'));
        $hand->addCard(new CardGraphic('clubs', 'K'));
        $hand->addCard(new CardGraphic('diamonds', '5'));
        
        // Ess måste räknas som 1 för att inte bli en "bust"

        $this->assertEquals(16, $hand->getTotal());
    }

    public function testGetTotalWithMultipleAces(): void
    {
        $hand = new CardHand();
        $hand->addCard(new CardGraphic('hearts', 'A'));
        $hand->addCard(new CardGraphic('clubs', 'A'));
        $hand->addCard(new CardGraphic('diamonds', '9'));
        
        // Båda essen måste räknas som 1 för att inte bli en "bust"
        $this->assertEquals(11, $hand->getTotal());
    }

    public function testIsBustFalse(): void
    {
        $hand = new CardHand();
        $hand->addCard(new CardGraphic('hearts', 'K'));
        $hand->addCard(new CardGraphic('clubs', '10'));
        
        $this->assertFalse($hand->isBust());
    }

    public function testIsBustTrue(): void
    {
        $hand = new CardHand();
        $hand->addCard(new CardGraphic('hearts', 'K'));
        $hand->addCard(new CardGraphic('clubs', 'Q'));
        $hand->addCard(new CardGraphic('diamonds', '5'));

        $this->assertTrue($hand->isBust());
    }

    public function testGetCardsReturnsArray(): void
    {
        $hand = new CardHand();
        $hand->addCard(new CardGraphic('hearts', '5'));
        $hand->addCard(new CardGraphic('clubs', '10'));

        $cards = $hand->getCards();

        $this->assertIsArray($cards);
        $this->assertCount(2, $cards);
        $this->assertInstanceOf(CardGraphic::class, $cards[0]);
    }
}
