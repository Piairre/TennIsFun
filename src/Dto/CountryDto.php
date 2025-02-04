<?php

namespace App\Dto;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class CountryDto
{
    public ?string $country;
    public ?string $countryCode;

    public function __construct(?string $country, ?string $countryCode)
    {
        $this->country = $country;
        $this->countryCode = $countryCode;
    }
}
