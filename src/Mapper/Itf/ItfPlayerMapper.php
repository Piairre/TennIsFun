<?php

namespace App\Mapper\Itf;

use App\Dto\CountryDto;
use App\Dto\PlayerDto;
use App\Mapper\Interface\PlayerMapperInterface;

class ItfPlayerMapper implements PlayerMapperInterface
{
    public function fromApiResponse(array $playerData): ?PlayerDto
    {
        if (is_null($playerData['id'])) {
            return null;
        }

        $playerDto = new PlayerDto(
            $playerData['id'],
            $playerData['person']['firstName'],
            $playerData['person']['lastName']
        );

        $countryDto = new CountryDto($playerData['person']['country']['_name'], $playerData['person']['country']['ISOcode']);

        $playerDto->setCountry($countryDto);

        return $playerDto;
    }
}
