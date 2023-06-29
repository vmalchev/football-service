<?php

namespace Sportal\FootballApi\Application\TournamentGroup\Output\Get;


use Sportal\FootballApi\Domain\TournamentGroup\ITournamentGroupProfile;

class Mapper
{

    private \Sportal\FootballApi\Application\Tournament\Output\Get\Mapper $mapper;

    public function __construct(\Sportal\FootballApi\Application\Tournament\Output\Get\Mapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function map(ITournamentGroupProfile $tournamentGroupProfile): ?Dto
    {
        $tournamentOrderEntities = $tournamentGroupProfile->getTournamentOrderEntities();
        $tournamentGroupEntity = $tournamentGroupProfile->getTournamentGroupEntity();

        $tournamentItems = [];
        foreach ($tournamentOrderEntities as $tournamentOrderEntity) {
            $tournamentItems[] = new TournamentItemDto(
                $this->mapper->map($tournamentOrderEntity->getTournamentEntity()),
                $tournamentOrderEntity->getSortOrder()
            );
        }

        return new Dto(
            $tournamentGroupEntity->getCode(),
            $tournamentGroupEntity->getName(),
            $tournamentGroupEntity->getDescription(),
            $tournamentItems
        );
    }

}