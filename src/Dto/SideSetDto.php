<?php

namespace App\Dto;

class SideSetDto
{
    public int $setScore;
    public ?int $setTieBreakScore;
    public bool $setWon;

    public function __construct(int $setScore, ?int $setTieBreakScore)
    {
        $this->setScore = $setScore;
        $this->setTieBreakScore = $setTieBreakScore === 0 ? null : $setTieBreakScore;
        $this->setWon = $this->isSetWon();
    }

    private function isSetWon(): bool
    {
        if (!is_null($this->setTieBreakScore)) {
            return $this->setScore === 7 && $this->setTieBreakScore >= 7;
        }

        return $this->setScore >= 6;
    }
}
