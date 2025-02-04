<?php

namespace App\Mapper\Atp;

use App\Dto\MatchDto;
use App\Dto\MatchStatusDto;
use App\Dto\PlayerDto;
use App\Dto\SideDto;
use App\Dto\SideSetDto;
use App\Mapper\Interface\MatchMapperInterface;
use App\Mapper\Interface\PlayerMapperInterface;
use App\Mapper\Interface\TournamentMapperInterface;

class AtpMatchMapper implements MatchMapperInterface
{
    private PlayerMapperInterface $playerMapper;
    private TournamentMapperInterface $tournamentMapper;

    public function __construct(PlayerMapperInterface $playerMapper, TournamentMapperInterface $tournamentMapper)
    {
        $this->playerMapper = $playerMapper;
        $this->tournamentMapper = $tournamentMapper;
    }

    public function fromApiResponse(array $matchData): MatchDto
    {
        $matchDto = new MatchDto(
            $matchData['MatchId'],
            $matchData['RoundName'],
            $matchData['MatchTimeTotal']
        );

        switch ($matchData['MatchStatus']) {
            case 'F':
                $matchDto->setMatchStatus(MatchStatusDto::STATUS_FINISHED);
                break;
            case 'P':
                $matchDto->setMatchStatus(MatchStatusDto::STATUS_IN_PROGRESS);
                break;
            default:
                $matchDto->setMatchStatus('unknown');
        }

        foreach (SideDto::ATP_SIDES_KEYS as $side) {
            $sideDto = $this->createSideDto($matchData[$side]);
            $matchDto->addSide($sideDto);
        }

        if (!is_null($matchData['WinningPlayerId'])) {
            // Set the winner side, loop through the sides to find the winner
            foreach ($matchDto->getSides() as $side) {
                foreach ($side->getPlayers() as $player) {
                    if ($player->getId() === $matchData['WinningPlayerId']) {
                        $side->setWinner(true);
                    }
                }
            }
        }

        return $matchDto;
    }

    private function createSideDto(array $sideData): SideDto
    {
        $sideDto = new SideDto();

        foreach (PlayerDto::ATP_PLAYERS_KEYS as $player) {
            $playerDto = $this->playerMapper->fromApiResponse($sideData[$player]);
            if ($playerDto) {
                $sideDto->addPlayer($playerDto);
            }
        }

        foreach ($sideData['SetScores'] as $set) {
            if (!is_null($set['SetScore'])) {
                $sideDto->addSideSet(new SideSetDto($set['SetScore'], $set['TieBreakScore']));
            }
        }

        return $sideDto;
    }
}
