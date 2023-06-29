<?php

namespace Sportal\FootballApi\Application\TournamentGroup\Input\Insert;

use Sportal\FootballApi\Domain\TournamentGroup\ITournamentGroupEntity;
use Sportal\FootballApi\Domain\TournamentGroup\ITournamentGroupEntityFactory;

class Mapper
{

    private ITournamentGroupEntityFactory $tournamentGroupEntityFactory;

    public function __construct(ITournamentGroupEntityFactory $tournamentGroupEntityFactory)
    {
        $this->tournamentGroupEntityFactory = $tournamentGroupEntityFactory;
    }

    public function map(Dto $dto): ITournamentGroupEntity
    {
        return $this->tournamentGroupEntityFactory
            ->setCode($dto->getCode())
            ->setName($dto->getName())
            ->setDescription($dto->getDescription())
            ->create();
    }

}