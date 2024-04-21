<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use App\Models\CardDeck;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ApiController extends AbstractController
{
#[Route("/api", name: "api")]
    public function api(): Response // Uppdaterad returtyp
    {
        $api = [
            [
            'link' => 'api/quote',
            'description' => 'Get a random quote'
        ],
    ];
        return $this->render('api.html.twig', ['api' => $api]);
    }

    #[Route("/api/quote", name: "api_quote")]
    public function apiQuote(): Response // Uppdaterad returtyp här
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
        // return new JsonResponse($data);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck", name: "api_deck" , methods:["GET"])]
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
    public function apiDeckShuffle( Request $request, SessionInterface $session): JsonResponse
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
        shuffle ($deck);

        return new JsonResponse($deck);
    }
    
    #[Route("/api/deck/draw", name: "api_deck_draw", methods:["GET", "POST"])]
    public function apiDeckDraw( Request $request, SessionInterface $session): JsonResponse
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
        shuffle ($deck);

        $drawNumber = $request->query->get('drawNumber', 1);
        $drawnCards = array_splice($deck, 0, $drawNumber);
        $session->set('deck', $deck);

        return new JsonResponse([
            'drawnCards' => $drawnCards,
            'deck' => $deck
        ]);
    }


}