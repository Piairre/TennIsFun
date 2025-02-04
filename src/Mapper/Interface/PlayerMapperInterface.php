<?php

namespace App\Mapper\Interface;

use App\Dto\PlayerDto;

interface PlayerMapperInterface
{
    public function fromApiResponse(array $playerData): ?PlayerDto;
}
