<?php


namespace Sportal\FootballApi\Application\Stage\Service;


use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Domain\Stage\IStageDeleteRule;
use Sportal\FootballApi\Domain\Stage\IStageModel;
use Sportal\FootballApi\Domain\Stage\IStageRepository;

class Destroy implements IService
{

    private IStageRepository $stageRepository;

    private IStageModel $stageModel;

    private IStageDeleteRule $stageDeleteRule;

    public function __construct(IStageRepository $stageRepository,
                                IStageModel $stageModel,
                                IStageDeleteRule $stageDeleteRule)
    {
        $this->stageRepository = $stageRepository;
        $this->stageModel = $stageModel;
        $this->stageDeleteRule = $stageDeleteRule;
    }

    /**
     * @param IDto $inputDto
     * @throws NoSuchEntityException
     */
    public function process(IDto $inputDto)
    {
        $stageId = $inputDto->getId();

        $stageEntity = $this->stageRepository->findById($stageId);
        if (is_null($stageEntity)) {
            throw new NoSuchEntityException('Stage ' . $stageId);
        }

        $this->stageDeleteRule->validate($stageEntity);

        $this->stageModel
            ->setStages([$stageEntity])
            ->withBlacklist()
            ->delete();
    }
}