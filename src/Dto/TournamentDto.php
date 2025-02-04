<?php

namespace App\Dto;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class TournamentDto
{
    public string $id;
    public string $name;
    public string $city;
    public ?string $country;
    public DateTime $startDate;
    public DateTime $endDate;
    public Collection $matches;

    /**
     * @throws \DateMalformedStringException
     */
    public function __construct(string $id, string $name, string $city, ?string $country, string $startDate, string $endDate)
    {
        $this->id = $id;
        $this->name = $name;
        $this->city = $city;
        $this->country = $country;
        $this->startDate = new DateTime($startDate);
        $this->endDate = new DateTime($endDate);
        $this->matches = new ArrayCollection();
    }

    public function addMatch(MatchDto $match): void
    {
        $this->matches->add($match);
        $match->tournament = $this;
    }
}
