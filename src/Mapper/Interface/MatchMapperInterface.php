<?php

namespace App\Mapper\Interface;

use App\Dto\MatchDto;

interface MatchMapperInterface
{
    public function fromApiResponse(array $matchData): MatchDto;
}
