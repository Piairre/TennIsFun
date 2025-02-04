<?php

namespace App\Dto;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class SideDto
{
    public const array ATP_SIDES_KEYS = ['PlayerTeam', 'OpponentTeam'];

    public Collection $players;
    public Collection $sideSets;
    public bool $isWinner = false;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->sideSets = new ArrayCollection();
    }

    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(PlayerDto $player): void
    {
        $this->players->add($player);
    }

    public function addSideSet(SideSetDto $sideSet): void
    {
        $this->sideSets->add($sideSet);
    }

    public function setWinner(bool $isWinner): void
    {
        $this->isWinner = $isWinner;
    }
}
