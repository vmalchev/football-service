<?php


namespace Sportal\FootballApi\Infrastructure\Adapter;


use Sportal\FootballApi\Adapter\EntityType;
use Sportal\FootballApi\Adapter\IEntitySource;
use Sportal\FootballApi\Adapter\IEntitySourceFactory;
use Sportal\FootballApi\Repository\CoachRepository;
use Sportal\FootballApi\Repository\PlayerRepository;
use Sportal\FootballApi\Repository\TeamRepository;
use Sportal\FootballApi\Repository\TournamentSeasonRepository;

class EntitySourceFactory implements IEntitySourceFactory
{
    /**
     * @var array
     */
    private $factoryMapping;

    public function __construct(
        PlayerRepository $playerRepository,
        CoachRepository $coachRepository,
        TeamRepository $teamRepository,
        TournamentSeasonRepository $tournamentSeasonRepository
    ) {
        $this->factoryMapping = [
            EntityType::PLAYER()->getValue() => $playerRepository,
            EntityType::TEAM()->getValue() => $teamRepository,
            EntityType::COACH()->getValue() => $coachRepository,
            EntityType::SEASON()->getValue() => $tournamentSeasonRepository
        ];
    }

    /**
     * @param EntityType $type
     * @return IEntitySource
     */
    public function getSource(EntityType $type): IEntitySource
    {
        $entitySource = $this->factoryMapping[$type->getValue()];

        if (!isset($entitySource)) {
            throw new \InvalidArgumentException('Type not found');
        }

        return $entitySource;
    }
}

