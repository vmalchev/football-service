<?php


namespace Sportal\FootballApi\Application\MatchEvent\Output\Get;

use Sportal\FootballApi\Application\Match\Output\Get\Score\TeamScoreDto;
use Sportal\FootballApi\Application\Player;
use Sportal\FootballApi\Domain\MatchEvent\IMatchEventEntity;

class Mapper
{
    /**
     * @var Player\Output\Get\Mapper
     */
    private Player\Output\Get\Mapper $playerMapper;


    public function __construct(Player\Output\Get\Mapper $playerMapper)
    {
        $this->playerMapper = $playerMapper;

    }

    public function map(?IMatchEventEntity $matchEventEntity): ?Dto
    {
        if (is_null($matchEventEntity)) {
            return null;
        }

        $score = null;
        if (!is_null($matchEventEntity->getGoalHome()) && !is_null($matchEventEntity->getGoalAway())) {
            $score = new TeamScoreDto($matchEventEntity->getGoalHome(), $matchEventEntity->getGoalAway());
        }

        return new Dto(
            $matchEventEntity->getId(),
            $matchEventEntity->getMatchId(),
            $matchEventEntity->getEventType()->getKey(),
            $matchEventEntity->getTeamPosition()->getKey(),
            $matchEventEntity->getMinute(),
            $matchEventEntity->getTeamId(),
            $this->playerMapper->map($matchEventEntity->getPrimaryPlayer()),
            $this->playerMapper->map($matchEventEntity->getSecondaryPlayer()),
            $score
        );
    }
}