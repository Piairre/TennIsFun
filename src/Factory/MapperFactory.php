<?php

namespace App\Factory;

use App\Mapper\Atp\AtpPlayerMapper;
use App\Mapper\Atp\AtpTournamentMapper;
use App\Mapper\Itf\ItfPlayerMapper;
use App\Mapper\Atp\AtpMatchMapper;
use App\Mapper\Itf\ItfMatchMapper;
use App\Mapper\Interface\PlayerMapperInterface;
use App\Mapper\Interface\MatchMapperInterface;
use App\Mapper\Itf\ItfTournamentMapper;

class MapperFactory
{
    public const string API_ATP = 'atp';
    public const string API_ITF = 'itf';

    private AtpPlayerMapper $atpPlayerMapper;
    private ItfPlayerMapper $itfPlayerMapper;
    private AtpMatchMapper $atpMatchMapper;
    private ItfMatchMapper $itfMatchMapper;

    public function __construct(
        AtpPlayerMapper $atpPlayerMapper,
        ItfPlayerMapper $itfPlayerMapper,
        AtpMatchMapper $atpMatchMapper,
        ItfMatchMapper $itfMatchMapper
    ) {
        $this->atpPlayerMapper = $atpPlayerMapper;
        $this->itfPlayerMapper = $itfPlayerMapper;
        $this->atpMatchMapper = $atpMatchMapper;
        $this->itfMatchMapper = $itfMatchMapper;
    }

    public function getPlayerMapper(string $api): PlayerMapperInterface
    {
        return match ($api) {
            self::API_ATP => $this->atpPlayerMapper,
            self::API_ITF => $this->itfPlayerMapper,
            default => throw new \InvalidArgumentException('API non supportée'),
        };
    }

    public function getMatchMapper(string $api): MatchMapperInterface
    {
        return match ($api) {
            self::API_ATP => $this->atpMatchMapper,
            self::API_ITF => $this->itfMatchMapper,
            default => throw new \InvalidArgumentException('API non supportée'),
        };
    }
}
