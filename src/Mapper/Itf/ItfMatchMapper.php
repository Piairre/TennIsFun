<?php

namespace App\Mapper\Itf;

use App\Dto\MatchDto;
use App\Dto\MatchStatusDto;
use App\Dto\SideDto;
use App\Dto\SideSetDto;
use App\Mapper\Interface\MatchMapperInterface;
use App\Mapper\Interface\PlayerMapperInterface;
use App\Mapper\Interface\TournamentMapperInterface;

class ItfMatchMapper implements MatchMapperInterface
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
            $matchData['id'],
            $matchData['round']['roundType']['shortName'],
            $matchData['duration']
        );

        switch ($matchData['matchStatus']['stateType']) {
            case 'live':
                $matchDto->setMatchStatus(MatchStatusDto::STATUS_IN_PROGRESS);
                break;
            default:
                $matchDto->setMatchStatus('unknown');
        }

        // order sides by sideOrder
        usort($matchData['sides'], function ($a, $b) {
            return $a['sideOrder'] <=> $b['sideOrder'];
        });

        foreach ($matchData['sides'] as $side) {
            $sideDto = $this->createSideDto($side);
            $matchDto->addSide($sideDto);
        }

        $tournament = $this->tournamentMapper->fromApiResponse($matchData['round']['draw']['event']);
        $tournament->addMatch($matchDto);

        return $matchDto;
    }

    private function createSideDto(array $sideData): SideDto
    {
        $sideDto = new SideDto();

        foreach ($sideData['sidePlayer'] as $player) {
            $playerDto = $this->playerMapper->fromApiResponse($player['player']);
            if ($playerDto) {
                $sideDto->addPlayer($playerDto);
            }
        }

        foreach ($sideData['sideSets'] as $set) {
            $sideDto->addSideSet(new SideSetDto($set['setScore'], $set['setTieBreakScore']));
        }

        return $sideDto;
    }
}
