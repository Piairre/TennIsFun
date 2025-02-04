<?php

namespace App\Dto;

class MatchStatusDto
{
    public const string STATUS_IN_PROGRESS = 'En cours';
    public const string STATUS_FINISHED = 'Terminé';

    public string $status;

    public function __construct(string $status)
    {
        $this->status = $status;
    }
}
