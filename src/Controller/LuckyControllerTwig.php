<?php
// använd denna controller för uppgiften och lägg till de routes som behövs

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
//use Symfony\Component\HttpFoundation\JsonResponse as HttpFoundationJsonResponse;


class LuckyControllerTwig extends AbstractController
{
    #[Route("/lucky", name: "lucky")]
    public function number(): Response
    {
        $date = new DateTime();
        $formattedDate = $date->format('Y-m-d');
        $number = random_int(0, 100);
        $quotes = [
        "sometimes the smallest things take up the most room in your heart",
        "we didn't realize we were making memories, we just knew we were having fun",
        "how lucky I am to have something that makes saying goodbye so hard",
        "rivers know this, there is no hurry. we shall get there someday",
        "you are brave than you believe, stronger than you seem, and smarter than you think"
        ];

        $quote = $quotes[array_rand($quotes)];

        $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF)); //genererar en random färg mellan 0 till 0xFFFFFF

        $data = [
            'number' => $number, //skickar till vyn
            'date' => $formattedDate,
            'quote' => $quote,
            'color' => $color,
        ];

        return $this->render('lucky.html.twig', $data);
    }
    #[Route("/", name: "home")]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route("/report", name: "report")]
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }

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
}
