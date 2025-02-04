<?php

namespace App\Dto;

class RoundDto
{
    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
