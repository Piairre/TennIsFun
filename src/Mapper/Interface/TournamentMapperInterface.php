<?php

namespace App\Mapper\Interface;

use App\Dto\TournamentDto;

interface TournamentMapperInterface
{
    public function fromApiResponse(array $tournamentData): TournamentDto;
}
