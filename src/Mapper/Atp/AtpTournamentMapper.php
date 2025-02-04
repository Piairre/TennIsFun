<?php

namespace App\Mapper\Atp;

use App\Dto\TournamentDto;
use App\Mapper\Interface\TournamentMapperInterface;

class AtpTournamentMapper implements TournamentMapperInterface
{

    public function fromApiResponse(array $tournamentData): TournamentDto
    {
        return new TournamentDto(
            $tournamentData['EventId'],
            $tournamentData['EventTitle'],
            $tournamentData['EventCity'],
            $tournamentData['EventCountry'],
            $tournamentData['EventStartDate'],
            $tournamentData['EventEndDate']
        );
    }
}
