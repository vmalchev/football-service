<?php

namespace Sportal\FootballApi\Application\TournamentGroupSelection\Service;

use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\Match\Exception\InvalidMatchInputException;
use Sportal\FootballApi\Application\TournamentGroupSelection\Input\Insert\CollectionDto;
use Sportal\FootballApi\Application\TournamentGroupSelection\Input\Insert\Mapper;
use Sportal\FootballApi\Domain\Match\Exception\InvalidMatchException;
use Sportal\FootballApi\Domain\TournamentGroupSelection\ITournamentGroupSelectionModel;
use Sportal\FootballApi\Domain\TournamentGroupSelection\ITournamentGroupSelectionRule;

class Insert implements IService
{

    private Mapper $mapper;

    private ITournamentGroupSelectionRule $tournamentGroupSelectionRule;

    private ITournamentGroupSelectionModel $tournamentGroupSelectionModel;

    public function __construct(Mapper $mapper,
                                ITournamentGroupSelectionModel $tournamentGroupSelectionModel,
                                ITournamentGroupSelectionRule $tournamentGroupSelectionRule)
    {
        $this->mapper = $mapper;
        $this->tournamentGroupSelectionModel = $tournamentGroupSelectionModel;
        $this->tournamentGroupSelectionRule = $tournamentGroupSelectionRule;
    }

    /**
     * @param CollectionDto $inputDto
     * @return void
     * @throws NoSuchEntityException
     * @throws InvalidMatchException
     * @throws InvalidMatchInputException
     * @throws \InvalidArgumentException
     */
    public function process(IDto $inputDto)
    {
        $this->tournamentGroupSelectionRule->validate($inputDto);

        $entities = $this->mapper->map($inputDto);

        $this->tournamentGroupSelectionModel
            ->setCode($inputDto->getCode())
            ->setDate(new \DateTimeImmutable($inputDto->getDate()))
            ->setEntities($entities)
            ->insert();
    }

}