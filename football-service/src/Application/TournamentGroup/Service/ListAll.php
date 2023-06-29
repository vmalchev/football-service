<?php

namespace Sportal\FootballApi\Application\TournamentGroup\Service;

use Sportal\FootballApi\Application\TournamentGroup\Output\ListAll\CollectionDto;
use Sportal\FootballApi\Application\TournamentGroup\Output\ListAll\Mapper;
use Sportal\FootballApi\Domain\TournamentGroup\ITournamentGroupRepository;

class ListAll
{

    private ITournamentGroupRepository $tournamentGroupRepository;

    private Mapper $mapper;

    public function __construct(ITournamentGroupRepository $tournamentGroupRepository,
                                Mapper $mapper)
    {
        $this->tournamentGroupRepository = $tournamentGroupRepository;
        $this->mapper = $mapper;
    }

    public function listAll(): CollectionDto
    {
        $entities = $this->tournamentGroupRepository->findAll();

        return $this->mapper->map($entities);
    }

}