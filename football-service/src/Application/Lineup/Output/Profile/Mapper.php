<?php


namespace Sportal\FootballApi\Application\Lineup\Output\Profile;

use Sportal\FootballApi\Application\Coach;
use Sportal\FootballApi\Application\Player;
use Sportal\FootballApi\Domain\Lineup\ILineupPlayerEntity;
use Sportal\FootballApi\Domain\Lineup\ILineupProfile;
use Sportal\FootballApi\Domain\LineupPlayerType\ILineupPlayerTypeEntity;

class Mapper
{
    private Coach\Output\Get\Mapper $coachMapper;
    private Player\Output\Get\Mapper $playerMapper;

    /**
     * Mapper constructor.
     * @param Coach\Output\Get\Mapper $coachMapper
     * @param Player\Output\Get\Mapper $playerMapper
     */
    public function __construct(Coach\Output\Get\Mapper $coachMapper, Player\Output\Get\Mapper $playerMapper)
    {
        $this->coachMapper = $coachMapper;
        $this->playerMapper = $playerMapper;
    }


    public function map(ILineupProfile $lineupProfile): Dto
    {
        $lineup = $lineupProfile->getLineup();

        $homeTeam = $lineupProfile->hasHomeTeam() ? new TeamDto(
            $lineup->getHomeTeamFormation(),
            $this->coachMapper->map($lineup->getHomeCoach()),
            $lineup->getHomeTeamId(),
            array_map([$this, 'mapPlayers'], $lineupProfile->getHomePlayers())
        ) : null;

        $awayTeam = $lineupProfile->hasAwayTeam() ? new TeamDto(
            $lineup->getAwayTeamFormation(),
            $this->coachMapper->map($lineup->getAwayCoach()),
            $lineup->getAwayTeamId(),
            array_map([$this, 'mapPlayers'], $lineupProfile->getAwayPlayers())
        ) : null;

        return new Dto(
            $lineup->getMatchId(),
            !is_null($lineup->getStatus()) ? $lineup->getStatus()->getKey() : null,
            $homeTeam,
            $awayTeam
        );
    }

    private function mapPlayers(ILineupPlayerEntity $entity): PlayerDto
    {
        return new PlayerDto(
            $this->mapPlayerType($entity->getType()),
            $this->playerMapper->map($entity->getPlayer()),
            $entity->getPositionX(),
            $entity->getPositionY(),
            $entity->getShirtNumber()
        );
    }

    private function mapPlayerType(ILineupPlayerTypeEntity $playerTypeEntity)
    {
        return new PlayerTypeDto(
            $playerTypeEntity->getId(),
            $playerTypeEntity->getName(),
            $playerTypeEntity->getCategory(),
            $playerTypeEntity->getCode()
        );
    }
}