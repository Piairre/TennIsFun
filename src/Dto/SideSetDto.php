<?php

namespace App\Dto;

class SideSetDto
{
    public int $setScore;
    public ?int $setTieBreakScore;

    public function __construct(string $setScore, ?string $setTieBreakScore)
    {
        $this->setScore = $setScore;
        $this->setTieBreakScore = $setTieBreakScore;
    }
}
