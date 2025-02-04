<?php

namespace App\Dto;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class MatchDto
{
    public string $id;
    public ?string $duration;
    public TournamentDto $tournament;
    public MatchStatusDto $matchStatus;
    public RoundDto $round;
    public Collection $sides;

    public function __construct(string $id, string $round, ?string $duration)
    {
        $this->id = $id;
        $this->round = new RoundDto($round);
        $this->duration = $duration;
        $this->sides = new ArrayCollection();
    }

    public function setMatchStatus(string $matchStatus): void
    {
        $this->matchStatus = new MatchStatusDto($matchStatus);
    }

    public function getSides(): Collection
    {
        return $this->sides;
    }

    public function addSide(SideDto $sideDto): void
    {
        $this->sides->add($sideDto);
    }
}
