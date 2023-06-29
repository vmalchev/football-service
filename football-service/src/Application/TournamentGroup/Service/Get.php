<?php

namespace Sportal\FootballApi\Application\TournamentGroup\Service;

use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\TournamentGroup\Output\Get\Dto;
use Sportal\FootballApi\Application\TournamentGroup\Output\Get\Mapper;
use Sportal\FootballApi\Domain\TournamentGroup\ITournamentGroupProfileBuilder;
use Sportal\FootballApi\Domain\TournamentGroup\ITournamentGroupRepository;

class Get implements IService
{

    private ITournamentGroupRepository $tournamentGroupRepository;

    private Mapper $mapper;

    private ITournamentGroupProfileBuilder $tournamentGroupProfileBuilder;

    public function __construct(ITournamentGroupRepository $tournamentGroupRepository,
                                Mapper $mapper,
                                ITournamentGroupProfileBuilder $tournamentGroupProfileBuilder)
    {
        $this->tournamentGroupRepository = $tournamentGroupRepository;
        $this->mapper = $mapper;
        $this->tournamentGroupProfileBuilder = $tournamentGroupProfileBuilder;
    }

    /**
     * @throws NoSuchEntityException
     */
    public function process(IDto $inputDto): ?Dto
    {
        $tournamentGroup = $this->tournamentGroupRepository->findByCode($inputDto->getCode());
        if (is_null($tournamentGroup)) {
            throw new NoSuchEntityException('Tournament group ' . $inputDto->getCode());
        }

        $tournamentGroupProfile = $this->tournamentGroupProfileBuilder->build($tournamentGroup);

        return $this->mapper->map($tournamentGroupProfile);
    }

}