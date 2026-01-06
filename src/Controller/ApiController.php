<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ApiController extends AbstractController
{
    #[Route("/api", name: "api")]
    public function api(): Response
    {
        $api = [
            [
                'link' => 'api/quote',
                'description' => 'Get a random quote'
            ],
            [
                'link' => '/api/deck',
                'description' => 'Get the deck of cards'
            ],
            [
                'link' => '/api/deck/shuffle',
                'description' => 'Shuffle the deck and save to session'
            ],
            [
                'link' => '/api/deck/draw',
                'description' => 'Draw one card from the deck'
            ],
            [
                'link' => '/api/game',
                'description' => 'Get current game state for Blackjack/21'
            ],
        ];
        return $this->render('api.html.twig', ['api' => $api]);
    }

    #[Route("/api/quote", name: "api_quote")]
    public function apiQuote(): Response
    {
        $quotes = [
            "sometimes the smallest things take up the most room in your heart",
            "we didn't realize we were making memories, we just knew we were having fun",
            "how lucky I am to have something that makes saying goodbye so hard",
            "rivers know this, there is no hurry. we shall get there someday",
            "you are braver than you believe, stronger than you seem, and smarter than you think"
        ];

        $quote = $quotes[array_rand($quotes)];
        $date = new DateTime();
        $today = $date->format('2024-04-06');

        $data = [
            'quotes' => $quote,
            'date' => $date,
            'today' => $today
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck", name: "api_deck", methods:["GET"])]
    public function apiDeck(): Response
    {
        $cards = [
            '🂡', '🂢', '🂣', '🂤', '🂥', '🂦', '🂧', '🂨',
            '🂩', '🂪', '🂫', '🂬', '🂭', '🂮', '🂱', '🂲',
            '🂳', '🂴', '🂵', '🂶', '🂷', '🂸', '🂹', '🂺',
            '🂻', '🂼', '🂽', '🂾', '🃁', '🃂', '🃃', '🃄',
            '🃅', '🃆', '🃇', '🃈', '🃉', '🃊', '🃋', '🃌',
            '🃍', '🃎', '🃑', '🃒', '🃓', '🃔', '🃕', '🃖',
            '🃗', '🃘', '🃙', '🃚', '🃛', '🃜', '🃝', '🃞'
        ];

        $deck = [];
        foreach ($cards as $card) {
            $deck[] = $card;
        }

        return new JsonResponse($deck);
    }

    #[Route("/api/deck/shuffle", name: "api_deck_shuffle", methods:["GET", "POST"])]
    public function apiDeckShuffle(Request $request, SessionInterface $session): JsonResponse
    {
        $deck = [
            '🂡', '🂢', '🂣', '🂤', '🂥', '🂦', '🂧', '🂨',
            '🂩', '🂪', '🂫', '🂬', '🂭', '🂮', '🂱', '🂲',
            '🂳', '🂴', '🂵', '🂶', '🂷', '🂸', '🂹', '🂺',
            '🂻', '🂼', '🂽', '🂾', '🃁', '🃂', '🃃', '🃄',
            '🃅', '🃆', '🃇', '🃈', '🃉', '🃊', '🃋', '🃌',
            '🃍', '🃎', '🃑', '🃒', '🃓', '🃔', '🃕', '🃖',
            '🃗', '🃘', '🃙', '🃚', '🃛', '🃜', '🃝', '🃞'
        ];

        shuffle($deck);
        $session->set('deck', $deck);
        return new JsonResponse(['deck' => $deck]);
    }

    #[Route("/api/deck/draw", name: "api_deck_draw", methods:["GET", "POST"])]
    public function apiDeckDraw(Request $request, SessionInterface $session): JsonResponse
    {
        $deck = $session->get('deck', [
            '🂡', '🂢', '🂣', '🂤', '🂥', '🂦', '🂧', '🂨',
            '🂩', '🂪', '🂫', '🂬', '🂭', '🂮', '🂱', '🂲',
            '🂳', '🂴', '🂵', '🂶', '🂷', '🂸', '🂹', '🂺',
            '🂻', '🂼', '🂽', '🂾', '🃁', '🃂', '🃃', '🃄',
            '🃅', '🃆', '🃇', '🃈', '🃉', '🃊', '🃋', '🃌',
            '🃍', '🃎', '🃑', '🃒', '🃓', '🃔', '🃕', '🃖',
            '🃗', '🃘', '🃙', '🃚', '🃛', '🃜', '🃝', '🃞'
        ]);
    
        if (count($deck) === 52) {
            shuffle($deck);
        }

        $drawNumber = $request->query->get('drawNumber', 1);
        $drawnCards = array_splice($deck, 0, $drawNumber);
        $session->set('deck', $deck);

        return new JsonResponse([
            'drawnCards' => $drawnCards,
            'deck' => $deck,
            'remaining' => count($deck)
        ]);
    }

    #[Route('/api/game', name: 'api_game', methods: ['GET'])]
    public function apiGame(SessionInterface $session): JsonResponse
    {
        $deck = $session->get('game_deck');
        $playerHand = $session->get('player_hand');
        $bankHand = $session->get('bank_hand');
        $gameOver = $session->get('game_over', false);
        $winner = $session->get('winner');

        if (!$deck || !$playerHand || !$bankHand) {
            return new JsonResponse([
                'error' => 'No current game session',
                'message' => 'Start a new game first'
            ], 404);
        }

        $playerCards = [];
        foreach ($playerHand->getCards() as $card) {
            $playerCards[] = [
                'suit' => $card->getSuit(),
                'value' => $card->getValue(),
                'unicode' => $card->getAsString(),
                'color' => $card->getColor()
            ];
        }

        // Konvertera bankhanden till array
        $bankCards = [];
        foreach ($bankHand->getCards() as $card) {
            $bankCards[] = [
                'suit' => $card->getSuit(),
                'value' => $card->getValue(),
                'unicode' => $card->getAsString(),
                'color' => $card->getColor()
            ];
        }

        $gameState = [
            'game_status' => $gameOver ? 'finished' : 'ongoing',
            'player' => [
                'cards' => $playerCards,
                'total_points' => $playerHand->getTotal(),
                'is_bust' => $playerHand->isBust(),
                'card_count' => count($playerCards)
            ],
            'bank' => [
                'cards' => $gameOver ? $bankCards : [],
                'total_points' => $gameOver ? $bankHand->getTotal() : null,
                'is_bust' => $gameOver ? $bankHand->isBust() : null,
                'card_count' => $gameOver ? count($bankCards) : null
            ],
            'deck' => [
                'cards_remaining' => $deck->getCount()
            ],
            'result' => $gameOver ? [
                'winner' => $winner,
                'message' => $winner === 'player' ? 'Player wins!' : 'Bank wins!'
            ] : null
        ];

        $response = new JsonResponse($gameState);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}