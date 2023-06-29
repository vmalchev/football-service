<?php

namespace Sportal\FootballApi\Application\TournamentGroup\Service;

use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\TournamentGroup\Input\Update\Mapper;
use Sportal\FootballApi\Application\TournamentOrder\Input\Upsert\Mapper as TournamentOrderMapper;
use Sportal\FootballApi\Domain\TournamentGroup\ITournamentGroupModel;
use Sportal\FootballApi\Domain\TournamentGroup\ITournamentGroupRepository;
use Sportal\FootballApi\Domain\TournamentGroup\ITournamentGroupRule;
use Sportal\FootballApi\Domain\TournamentOrder\ITournamentOrderRule;

class Update implements IService
{

    private ITournamentGroupModel $tournamentGroupModel;

    private ITournamentGroupRepository $tournamentGroupRepository;

    private Mapper $mapper;

    private ITournamentGroupRule $tournamentGroupRule;

    private ITournamentOrderRule $tournamentOrderRule;

    private TournamentOrderMapper $tournamentOrderMapper;

    public function __construct(ITournamentGroupModel $tournamentGroupModel,
                                Mapper $mapper,
                                ITournamentGroupRepository $tournamentGroupRepository,
                                ITournamentGroupRule $tournamentGroupRule,
                                TournamentOrderMapper $tournamentOrderMapper,
                                ITournamentOrderRule $tournamentOrderRule)
    {
        $this->tournamentGroupModel = $tournamentGroupModel;
        $this->mapper = $mapper;
        $this->tournamentGroupRepository = $tournamentGroupRepository;
        $this->tournamentGroupRule = $tournamentGroupRule;
        $this->tournamentOrderMapper = $tournamentOrderMapper;
        $this->tournamentOrderRule = $tournamentOrderRule;
    }

    public function process(IDto $inputDto)
    {
        $group = $this->tournamentGroupRepository->findByCode($inputDto->getCode());

        if (is_null($group)) {
            throw new NoSuchEntityException('Tournament group ' . $inputDto->getCode());
        }

        $tournamentGroupEntity = $this->mapper->map($inputDto);

        $tournamentOrderEntities = $this->tournamentOrderMapper->map($inputDto);

        $this->tournamentGroupRule->validate($tournamentGroupEntity, false);
        $this->tournamentOrderRule->validate($tournamentOrderEntities);

        $this->tournamentGroupModel
            ->setEntity($tournamentGroupEntity)
            ->setTournaments($tournamentOrderEntities)
            ->update();
    }

}