<?php

namespace Sportal\FootballApi\Application\TournamentGroupSelection\Input\Insert;

use Sportal\FootballApi\Domain\TournamentGroupSelection\ITournamentGroupSelectionEntityFactory;
use Sportal\FootballApi\Infrastructure\TournamentGroupSelection\TournamentGroupSelectionEntity;

class Mapper
{

    private ITournamentGroupSelectionEntityFactory $entityFactory;

    public function __construct(ITournamentGroupSelectionEntityFactory $entityFactory)
    {
        $this->entityFactory = $entityFactory;
    }

    /**
     * @param CollectionDto $dto
     * @return TournamentGroupSelectionEntity[]
     */
    public function map(CollectionDto $dto): array
    {
        $entities = [];
        $ids = array_unique(array_map(fn($dto) => $dto->getMatchId(), $dto->getMatches()));
        foreach ($ids as $id) {
            $entities[] = $this->entityFactory
                ->setCode($dto->getCode())
                ->setDate(new \DateTimeImmutable($dto->getDate()))
                ->setMatchId($id)
                ->create();
        }

        return $entities;
    }

}