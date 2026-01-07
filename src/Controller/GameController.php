<?php

// blackjack spel controller

namespace App\Controller;

use App\Blackjack\CardHand;
use App\Blackjack\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route('/game', name: 'game_home')]
    public function home(): Response
    {
        return $this->render('Blackjack/home.html.twig');
    }

    #[Route('/game/init', name: 'game_init')]
    public function init(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $deck->shuffle();

        $playerHand = new CardHand();
        $bankHand = new CardHand();

        $session->set('game_deck', $deck);
        $session->set('player_hand', $playerHand);
        $session->set('bank_hand', $bankHand);
        $session->set('game_over', false);
        $session->set('winner', null);

        return $this->redirectToRoute('game_play');
    }

    #[Route('/game/play', name: 'game_play')]
    public function play(SessionInterface $session): Response
    {
        $deck = $session->get('game_deck');
        $playerHand = $session->get('player_hand');
        $bankHand = $session->get('bank_hand');
        $gameOver = $session->get('game_over', false);
        $winner = $session->get('winner');

        if (!$deck || !$playerHand || !$bankHand) {
            return $this->redirectToRoute('game_init');
        }

        return $this->render('Blackjack/play.html.twig', [
            'player_hand' => $playerHand,
            'bank_hand' => $bankHand,
            'deck_count' => $deck->getCount(),
            'game_over' => $gameOver,
            'winner' => $winner
        ]);
    }

    #[Route('/game/draw', name: 'game_draw')]
    public function draw(SessionInterface $session): Response
    {
        $deck = $session->get('game_deck');
        $playerHand = $session->get('player_hand');

        $card = $deck->draw();
        if ($card) {
            $playerHand->addCard($card);
        }

        if ($playerHand->isBust()) {
            $session->set('game_over', true);
            $session->set('winner', 'bank');
        }

        $session->set('game_deck', $deck);
        $session->set('player_hand', $playerHand);

        return $this->redirectToRoute('game_play');
    }

    #[Route('/game/stand', name: 'game_stand')]
    public function stand(SessionInterface $session): Response
    {
        $deck = $session->get('game_deck');
        $playerHand = $session->get('player_hand');
        $bankHand = $session->get('bank_hand');

        while ($bankHand->getTotal() < 17) {
            $card = $deck->draw();
            if ($card) {
                $bankHand->addCard($card);
            }
        }

        $playerTotal = $playerHand->getTotal();
        $bankTotal = $bankHand->getTotal();

        if ($bankHand->isBust() || $playerTotal > $bankTotal) {
            $winner = 'player';
        } else {
            $winner = 'bank';
        }

        $session->set('game_deck', $deck);
        $session->set('bank_hand', $bankHand);
        $session->set('game_over', true);
        $session->set('winner', $winner);

        return $this->redirectToRoute('game_play');
    }

    #[Route('/game/doc', name: 'game_doc')]
    public function documentation(): Response
    {
        return $this->render('Blackjack/doc.html.twig');
    }
}
