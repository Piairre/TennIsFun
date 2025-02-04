<?php

namespace App\Service;

use App\Dto\MatchDto;
use App\Factory\MapperFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class TennisClientService
{
    protected MapperFactory $mapperFactory;
    protected string $api;

    public function __construct(MapperFactory $mapperFactory)
    {
        $this->mapperFactory = $mapperFactory;
    }

    protected function setApi(string $api): void
    {
        $this->api = $api;
    }

    /**
     * @return ArrayCollection<int, MatchDto>
     */
    abstract public function fetchLiveMatches(): ArrayCollection;

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    protected function handleHttpResponse(ResponseInterface $response): array
    {
        if ($response->getStatusCode() !== 200) {
            throw new Exception("Erreur API: " . $response->getContent(false));
        }

        return $response->toArray();
    }

    protected function safeJsonDecode(string $jsonString): array
    {
        $decoded = json_decode(strip_tags($jsonString), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Erreur de décodage JSON: " . json_last_error_msg());
        }

        return $decoded;
    }
}
