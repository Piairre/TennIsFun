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

        $matchDto->setMatchStatus($matchData['matchStatus']['stateType']);
        switch ($matchData['matchStatus']['name']) {
            case 'In Progress':
                $matchDto->setMatchStatus(MatchStatusDto::STATUS_IN_PROGRESS);
                break;
            case 'Coin Toss Started':
                $matchDto->setMatchStatus(MatchStatusDto::COIN_TOSS);
                break;
            case 'Umpire on Court':
                $matchDto->setMatchStatus(MatchStatusDto::WILL_START);
                break;
            default:
                $matchDto->setMatchStatus($matchData['matchStatus']['displayName']);
        }

        // order sides by sideOrder
        usort($matchData['sides'], function ($a, $b) {
            return $a['sideOrder'] <=> $b['sideOrder'];
        });

        foreach ($matchData['sides'] as $side) {
            $sideDto = $this->createSideDto($side, $matchDto->isFinished());

            if ($sideDto->getId() === $matchData['currentServerSideId']) {
                $sideDto->isServing(true);
            }

            $matchDto->addSide($sideDto);
        }

        $tournament = $this->tournamentMapper->fromApiResponse($matchData['round']['draw']['event']);
        $tournament->addMatch($matchDto);

        return $matchDto;
    }

    private function createSideDto(array $sideData, bool $isFinished): SideDto
    {
        $sideDto = new SideDto($sideData['id']);

        foreach ($sideData['sidePlayer'] as $player) {
            $playerDto = $this->playerMapper->fromApiResponse($player['player']);
            if ($playerDto) {
                $sideDto->addPlayer($playerDto);
            }
        }

        // order sets by $sideData['sideSets']['setNumber']
        usort($sideData['sideSets'], function ($a, $b) {
            return $a['setNumber'] <=> $b['setNumber'];
        });

        foreach ($sideData['sideSets'] as $set) {
            $sideDto->addSideSet(new SideSetDto($set['setScore'], $set['setTieBreakScore']));
        }

        if (!$isFinished){
            // Add a default side set if the match will start
            if ($sideDto->getSideSets()->count() === 0) {
                $sideDto->addSideSet(new SideSetDto(0, null));
            }

            $sideDto->setGameScore($sideData['score']);
        }

        return $sideDto;
    }
}
