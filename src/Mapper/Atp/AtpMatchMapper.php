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
            case 'C':
                $matchDto->setMatchStatus(MatchStatusDto::WILL_START);
                break;
            default:
                $matchDto->setMatchStatus(MatchStatusDto::STATUS_IN_PROGRESS);
        }

        foreach (SideDto::ATP_SIDES_KEYS as $side) {
            $sideDto = $this->createSideDto($matchData, $side, $matchDto->isFinished());
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

        if (!is_null($matchData['ServerTeam']) && !$matchDto->isFinished()) {
            $matchDto->getSides()->get($matchData['ServerTeam'])->isServing(true);
        }

        return $matchDto;
    }

    private function createSideDto(array $sides, string $sideKey, bool $isFinished): SideDto
    {
        $sideData = $sides[$sideKey];
        //dd($sideData);
        $sideDto = new SideDto($sideData['Player']['PlayerId']);

        foreach (PlayerDto::ATP_PLAYERS_KEYS as $player) {
            $playerDto = $this->playerMapper->fromApiResponse($sideData[$player]);
            if ($playerDto) {
                $sideDto->addPlayer($playerDto);
            }
        }

        $opponentSideKey = array_values(array_diff(SideDto::ATP_SIDES_KEYS, [$sideKey]))[0];

        foreach ($sideData['SetScores'] as $setKey => $set) {
            if (!is_null($set['SetScore'])) {
                $tieBreakScore = $set['TieBreakScore'];
                $opponentSideScore = $sides[$opponentSideKey]['SetScores'][$setKey];

                if ($set['SetScore'] === 7 && $opponentSideScore['SetScore'] === 6) {
                    // Tie break score is 7-6, get the opponent score and add 2
                    $tieBreakScore = $sides[$opponentSideKey]['SetScores'][$setKey]['TieBreakScore'] + 2;

                    if ($tieBreakScore < 7) {
                        $tieBreakScore = 7;
                    }
                }

                $sideDto->addSideSet(new SideSetDto($set['SetScore'], $tieBreakScore));
            }
        }

        if (!$isFinished) {
            $sideDto->setGameScore($sideData['GameScore']);
        }

        return $sideDto;
    }
}
