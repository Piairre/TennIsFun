<?php

namespace App\Mapper\Itf;

use App\Dto\PlayerDto;
use App\Mapper\Interface\PlayerMapperInterface;

class ItfPlayerMapper implements PlayerMapperInterface
{
    public function fromApiResponse(array $playerData): ?PlayerDto
    {
        if (is_null($playerData['id'])) {
            return null;
        }

        return new PlayerDto(
            $playerData['id'],
            $playerData['person']['firstName'],
            $playerData['person']['lastName'],
            $playerData['person']['country']['name']
        );
    }
}
