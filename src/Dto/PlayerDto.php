<?php

namespace App\Dto;

class PlayerDto
{
    public const array ATP_PLAYERS_KEYS = ['Player', 'Partner'];

    public string $id;
    public string $firstName;
    public string $lastName;
    public string $formattedName;
    public string $countryCode;

    public function __construct(string $id, string $firstName, string $lastName, string $countryCode)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->countryCode = $countryCode;
        $this->formattedName = $this->formatFullName($firstName, $lastName);
    }

    public function getId(): string
    {
        return $this->id;
    }
    private function formatFullName(string $firstName, string $lastName): string
    {
        return strtoupper(substr($firstName, 0, 1)) . '. ' . ucfirst($lastName);
    }
}
