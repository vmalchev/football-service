<?php

namespace Sportal\FootballApi\Application\TournamentOrder\Input\Upsert;

use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Domain\TournamentOrder\ITournamentOrderEntity;
use Sportal\FootballApi\Domain\TournamentOrder\ITournamentOrderEntityFactory;

class Mapper
{

    private ITournamentOrderEntityFactory $tournamentOrderEntityFactory;

    public function __construct(ITournamentOrderEntityFactory $tournamentOrderEntityFactory)
    {
        $this->tournamentOrderEntityFactory = $tournamentOrderEntityFactory;
    }

    /**
     * @param IDto $dto
     * @return ITournamentOrderEntity[]
     */
    public function map(IDto $dto): array
    {
        return array_map(
            fn($tournamentItem) => $this->tournamentOrderEntityFactory
                ->setClientCode($dto->getCode())
                ->setTournamentId($tournamentItem->getTournamentId())
                ->setSortorder($tournamentItem->getSortOrder())
                ->create(), $dto->getTournaments()
        );
    }

}