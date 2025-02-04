<?php

namespace App\Mapper\Itf;

use App\Dto\TournamentDto;
use App\Mapper\Interface\TournamentMapperInterface;

class ItfTournamentMapper implements TournamentMapperInterface
{

    public function fromApiResponse(array $tournamentData): TournamentDto
    {
        return new TournamentDto(
            $tournamentData['id'],
            $tournamentData['officialName'],
            $tournamentData['venue']['city'] ?? '',
            $tournamentData['venue']['country']['_name'] ?? '',
            $tournamentData['startDate'],
            $tournamentData['endDate']
        );
    }
}
