<?php


namespace Sportal\FootballApi\Application\StageTeam\Service;


use Sportal\FootballApi\Application\Exception\DuplicateKeyException;
use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\StageTeam\Input\Update\Mapper;
use Sportal\FootballApi\Domain\Stage\IStageRepository;
use Sportal\FootballApi\Domain\StageTeam\IStageTeamModel;
use Sportal\FootballApi\Domain\StageTeam\IStageTeamRule;

class Update implements IService
{

    private IStageTeamModel $stageTeamModel;

    private IStageRepository $stageRepository;

    private Mapper $mapper;

    private IStageTeamRule $stageTeamRule;

    public function __construct(IStageRepository $stageRepository,
                                IStageTeamModel $stageTeamModel,
                                Mapper $mapper,
                                IStageTeamRule $stageTeamRule)
    {
        $this->stageRepository = $stageRepository;
        $this->stageTeamModel = $stageTeamModel;
        $this->mapper = $mapper;
        $this->stageTeamRule = $stageTeamRule;
    }

    /**
     * @throws NoSuchEntityException|DuplicateKeyException
     */
    public function process(IDto $inputDto)
    {
        $stageId = $inputDto->getStageId();

        $stage = $this->stageRepository->findById($stageId);

        if (is_null($stage)) {
            throw new NoSuchEntityException('Stage ' . $stageId);
        }

        $stageTeamEntities = $this->mapper->map($inputDto);

        $this->stageTeamRule->validate($stageTeamEntities);

        $this->stageTeamModel
            ->setStageTeams($stageTeamEntities, $stage)
            ->withBlacklist()
            ->update();
    }

}