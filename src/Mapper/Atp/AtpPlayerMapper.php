<?php

namespace App\Mapper\Atp;

use App\Dto\CountryDto;
use App\Dto\PlayerDto;
use App\Mapper\Interface\PlayerMapperInterface;

class AtpPlayerMapper implements PlayerMapperInterface
{
    public function fromApiResponse(array $playerData): ?PlayerDto
    {
        if (is_null($playerData['PlayerId'])) {
            return null;
        }

        $playerDto = new PlayerDto(
            $playerData['PlayerId'],
            $playerData['PlayerFirstName'],
            $playerData['PlayerLastName']
        );

        $countryDto = new CountryDto(
            $playerData['PlayerCountryName'],
            $playerData['PlayerCountry']
        );

        $playerDto->setCountry($countryDto);

        return $playerDto;
    }
}
