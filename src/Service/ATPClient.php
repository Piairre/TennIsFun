<?php

namespace App\Service;

use App\Factory\MapperFactory;
use App\Mapper\Interface\TournamentMapperInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ATPClient extends TennisClientService
{
    public const string API_BASE_URL = 'https://www.atptour.com/en/-';

    private HttpClientInterface $client;

    private TournamentMapperInterface $tournamentMapper;

    public function __construct(
        HttpClientInterface $client,
        MapperFactory $mapperFactory,
        TournamentMapperInterface $tournamentMapper
    )
    {
        $this->setApi('atp');
        $this->client = $client;
        parent::__construct($mapperFactory);
        $this->tournamentMapper = $tournamentMapper;
    }

    public function fetchLiveMatches(): ArrayCollection
    {
        // Utilisation de FlareSolverr pour contourner les restrictions
        $flareSolveResponse = $this->client->request('POST', 'http://flaresolverr:8191/v1', [
            'json' => [
                "cmd" => "request.get",
                "url" => "https://app.atptour.com/api/v2/gateway/livematches/website?scoringTournamentLevel=challenger",
                "maxTimeout" => 10000
            ],
        ]);

        $response = $this->handleHttpResponse($flareSolveResponse);

        if ($response['status'] !== 'ok') {
            throw new Exception("Erreur API ATP: " . $response['status']);
        }

        $values = $this->safeJsonDecode($response['solution']['response']);
        $matches = $values['Data'];

        $matchMapper = $this->mapperFactory->getMatchMapper($this->api);
        $response = new ArrayCollection();

        foreach ($matches['LiveMatchesTournamentsOrdered'] as $tournament) {

            $tournamentDto = $this->tournamentMapper->fromApiResponse($tournament);

            foreach ($tournament['LiveMatches'] as $match) {
                $matchDto = $matchMapper->fromApiResponse($match);
                $tournamentDto->addMatch($matchDto);

                $response->add($matchDto);
            }
        }

        return $response;
    }
}
