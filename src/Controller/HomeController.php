<?php

namespace App\Controller;

use App\Factory\MapperFactory;
use App\Service\ATPClient;
use App\Service\ITFClient;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    private ATPClient $atpClient;
    private ITFClient $itfClient;

    public function __construct(ATPClient $atpClient, ITFClient $itfClient)
    {
        $this->atpClient = $atpClient;
        $this->itfClient = $itfClient;
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/{type}/home', name: 'circuit_home')]
    public function circuitIndex(string $type): Response
    {
        $client = $type === MapperFactory::API_ATP ? $this->atpClient : $this->itfClient;

        try {
            $matchesLive = $client->fetchLiveMatches();
        } catch (Exception $e) {
            dd($e);
            $matchesLive = [];
        }

        return $this->render('circuit/home.html.twig', [
            'type' => $type,
            'matchesLive' => $matchesLive,
        ]);
    }
}
