<?php

namespace App\Dto;

class MatchStatusDto
{
    public const string STATUS_IN_PROGRESS = 'En cours';
    public const string STATUS_FINISHED = 'Terminé';
    public const string WILL_START = 'Va commencer';
    public const string COIN_TOSS = 'Tirage au sort';

    public string $status;
    public bool $isFinished;

    public function __construct(string $status)
    {
        $this->status = $status;
        $this->isFinished = $status === self::STATUS_FINISHED;
    }
}
