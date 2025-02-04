<?php

namespace App\Service;

use App\Factory\MapperFactory;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ITFClient extends TennisClientService
{
    private HttpClientInterface $client;
    public const string API_BASE_URL = 'https://api.itf-production.sports-data.stadion.io';

    public function __construct(HttpClientInterface $client, MapperFactory $mapperFactory)
    {
        parent::__construct($mapperFactory);
        $this->client = $client;
        $this->setApi('itf');
    }

    public function fetchLiveMatches(int $limit = 10): ArrayCollection
    {
        $currentDate = (new DateTime())->format('Y-m-d');

        $response = $this->client->request('GET', self::API_BASE_URL . '/custom/matchRibbon/' . $currentDate . '/wtt', [
            'query' => ['limit' => $limit],
        ]);

        $matches = $this->handleHttpResponse($response);

        $matchMapper = $this->mapperFactory->getMatchMapper($this->api);
        $liveMatches = new ArrayCollection();

        if (is_array($matches['data'])) {
            foreach ($matches['data'] as $match) {
                $matchDto = $matchMapper->fromApiResponse($match);
                $liveMatches->add($matchDto);
            }
        }

        return $liveMatches;
    }
}
