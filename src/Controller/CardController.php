<?php

namespace App\Controller;

use App\Models\Card;
use App\Models\CardGraphic;
use App\Models\CardDeck;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CardController extends AbstractController
{
    // route för card landningssida
    #[Route("/card", name: "card_start")]
    public function home(): Response
    {
        return $this->render('card/home.html.twig');
    }


    // route för card/deck visar samtliga kort i kortleken sorterade per färg och värde.
    #[Route("/card/deck", name: "card_deck")]
    public function deck(): Response
    {
        $cardDeck = new CardDeck();
        $deck = $cardDeck->getDeck();

        $cardsData = array_map(function ($card) use ($cardDeck) {
            return [
                'string' => $cardDeck->getCardString($card),
                'color' => $card->color
            ];
        }, $deck);

        $data = [
            "cardDeck" => $cardsData,
        ];

        return $this->render('card/deck.html.twig', $data);
    }


    /*// Skapa en sida card/deck/shuffle som visar samtliga kort i kortleken när den har blandats.
    #[Route("/card/deck/shuffle", name: "card_shuffle", methods: ['GET','POST'])]
    public function shuffle(SessionInterface $session, CardGraphic $cardGraphic): Response
    {
        $card = $cardGraphic->getCards();
        shuffle($card); //blandar korten
        $session->set('deck', $card);
        return $this->render('card/shuffle.html.twig', [
            'cards' => $card,
            'remaining' => count($card),
            'cardColors' => $cardGraphic->getCardColors()
        ]);
    }*/

    // src/Controller/CardController.php
    #[Route("/card/deck/shuffle", name: "card_shuffle", methods: ['GET','POST'])]
    public function shuffle(SessionInterface $session, CardGraphic $cardGraphic): Response
    {
        $card = $cardGraphic->getCards();
        if (empty($card)) {
            $cardGraphic->shuffleCards();
            $card = $cardGraphic->getCards();
        }
        shuffle($card); //blandar korten
        $session->set('deck', $card);
        return $this->render('card/shuffle.html.twig', [
            'cards' => $card,
            'remaining' => count($card),
            'cardColors' => $cardGraphic->getCardColors()
        ]);
    }

    // Skapa en sida card/draw som drar ett kort från kortleken och visar det.
    #[Route("/card/deck/draw/{number}", name: "card_draw", requirements: ['number' => '\d+'], methods: ['GET','POST'])]
    public function drawCards(SessionInterface $session, int $number = 1): Response
    {
        $deck = $session->get('deck', []);
        if (!is_array($deck)) {

            $deck = [
                '🂡', '🂢', '🂣', '🂤', '🂥', '🂦', '🂧', '🂨',
                '🂩', '🂪', '🂫', '🂬', '🂭', '🂮', '🂱', '🂲',
                '🂳', '🂴', '🂵', '🂶', '🂷', '🂸', '🂹', '🂺',
                '🂻', '🂼', '🂽', '🂾', '🃁', '🃂', '🃃', '🃄',
                '🃅', '🃆', '🃇', '🃈', '🃉', '🃊', '🃋', '🃌',
                '🃍', '🃎', '🃑', '🃒', '🃓', '🃔', '🃕', '🃖',
                '🃗', '🃘', '🃙', '🃚', '🃛', '🃜', '🃝', '🃞'

            ];
        }
        $CardGraphic = new CardGraphic();
        $CardGraphic->setDeck($deck ?? $CardGraphic->getDeck());
        $CardGraphic->setCardColors();
        $pullCard = $CardGraphic->drawCard($number);
        $session->set('deck', $CardGraphic->getDeck());
        return $this->render('card/draw.html.twig', [
            'card' => $pullCard,
            'remaining' => count($CardGraphic->getDeck()),
            'cardColors' => $CardGraphic->getCardColors()
        ]);
    }

    #[Route("/session/show", name: "session_show")]
    public function showSession(SessionInterface $session): Response
    {
        $deck = $session->get('deck', []);
        return $this->render('card/session.html.twig', ['deck' => $deck]);
    }

    #[Route("/session/clear", name: "session_clear")]
    public function clearSession(SessionInterface $session): Response
    {
        $session->clear(); //rensa sessionen
        $this->addFlash('success', 'Session cleared'); //lägger till flashmeddelande
        return $this->render('card/delete.html.twig');
    }
}
