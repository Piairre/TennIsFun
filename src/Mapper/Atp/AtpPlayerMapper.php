<?php

namespace App\Mapper\Atp;

use App\Dto\PlayerDto;
use App\Mapper\Interface\PlayerMapperInterface;

class AtpPlayerMapper implements PlayerMapperInterface
{
    public function fromApiResponse(array $playerData): ?PlayerDto
    {
        if (is_null($playerData['PlayerId'])) {
            return null;
        }

        return new PlayerDto(
            $playerData['PlayerId'],
            $playerData['PlayerFirstName'],
            $playerData['PlayerLastName'],
            $playerData['PlayerCountry']
        );
    }
}
