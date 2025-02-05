<?php

namespace App\Dto;

class PlayerDto
{
    public const array ATP_PLAYERS_KEYS = ['Player', 'Partner'];

    public string $id;
    public string $firstName;
    public string $lastName;
    public string $formattedName;
    public ?CountryDto $country = null;

    public function __construct(string $id, string $firstName, string $lastName)
    {
        $this->id = $id;
        $this->firstName = ucfirst(strtolower($firstName));
        $this->lastName = ucfirst(strtolower($lastName));
        $this->formattedName = $this->formatFullName($this->firstName, $this->lastName);
    }

    public function getId(): string
    {
        return $this->id;
    }
    private function formatFullName(string $firstName, string $lastName): string
    {
        return substr($firstName, 0, 1) . '. ' . $lastName;
    }

    public function setCountry(CountryDto $country): void
    {
        $this->country = $country;
    }
}
