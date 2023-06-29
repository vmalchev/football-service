<?php

namespace Sportal\FootballApi\Application\TournamentGroup\Service;

use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\TournamentGroup\Input\Insert\Mapper;
use Sportal\FootballApi\Application\TournamentOrder\Input\Upsert\Mapper as TournamentOrderMapper;
use Sportal\FootballApi\Domain\TournamentGroup\ITournamentGroupModel;
use Sportal\FootballApi\Domain\TournamentGroup\ITournamentGroupRule;
use Sportal\FootballApi\Domain\TournamentOrder\ITournamentOrderRule;

class Insert implements IService
{

    private Mapper $mapper;

    private ITournamentGroupModel $tournamentGroupModel;

    private ITournamentGroupRule $tournamentGroupRule;

    private ITournamentOrderRule $tournamentOrderRule;

    private TournamentOrderMapper $tournamentOrderMapper;

    public function __construct(Mapper $mapper,
                                ITournamentGroupModel $tournamentGroupModel,
                                ITournamentGroupRule $tournamentGroupRule,
                                TournamentOrderMapper $tournamentOrderMapper,
                                ITournamentOrderRule $tournamentOrderRule)
    {
        $this->mapper = $mapper;
        $this->tournamentGroupModel = $tournamentGroupModel;
        $this->tournamentGroupRule = $tournamentGroupRule;
        $this->tournamentOrderMapper = $tournamentOrderMapper;
        $this->tournamentOrderRule = $tournamentOrderRule;
    }

    public function process(IDto $inputDto): void
    {
        $tournamentGroupEntity = $this->mapper->map($inputDto);

        $tournamentOrderEntities = $this->tournamentOrderMapper->map($inputDto);

        $this->tournamentGroupRule->validate($tournamentGroupEntity, true);
        $this->tournamentOrderRule->validate($tournamentOrderEntities);

        $this->tournamentGroupModel
            ->setEntity($tournamentGroupEntity)
            ->setTournaments($tournamentOrderEntities)
            ->insert();
    }

}