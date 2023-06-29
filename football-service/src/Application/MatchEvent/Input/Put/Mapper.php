<?php


namespace Sportal\FootballApi\Application\MatchEvent\Input\Put;


use Sportal\FootballApi\Domain\MatchEvent\IMatchEventEntity;
use Sportal\FootballApi\Domain\MatchEvent\IMatchEventEntityFactory;
use Sportal\FootballApi\Domain\MatchEvent\MatchEventType;
use Sportal\FootballApi\Domain\MatchEvent\TeamPositionStatus;

class Mapper
{
    private IMatchEventEntityFactory $factory;

    /**
     * Mapper constructor.
     * @param IMatchEventEntityFactory $factory
     */
    public function __construct(IMatchEventEntityFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param Dto $dto
     * @return IMatchEventEntity[]
     */
    public function map(Dto $dto): array
    {
        return array_map(function (MatchEventDto $matchEvent) use ($dto) {
            return $this->factory->setEmpty()
                ->setId($matchEvent->getId())
                ->setMatchId($dto->getMatchId())
                ->setEventType(MatchEventType::forKey($matchEvent->getTypeCode()))
                ->setTeamPosition(TeamPositionStatus::forKey($matchEvent->getTeamPosition()))
                ->setMinute($matchEvent->getMinute())
                ->setPrimaryPlayerId($matchEvent->getPrimaryPlayerId())
                ->setSecondaryPlayerId($matchEvent->getSecondaryPlayerId())
                ->setSortOrder($matchEvent->getSortOrder())
                ->create();
        }, $dto->getEvents());
    }


}