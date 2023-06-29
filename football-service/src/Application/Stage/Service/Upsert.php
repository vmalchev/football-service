<?php


namespace Sportal\FootballApi\Application\Stage\Service;


use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Domain\Season\ISeasonRepository;
use Sportal\FootballApi\Domain\Stage\IStageModel;
use Sportal\FootballApi\Application\Stage;
use Sportal\FootballApi\Domain\Stage\IStageRule;

class Upsert implements IService
{

    private IStageModel $stageModel;

    private Stage\Input\Upsert\Mapper $inputMapper;

    private Stage\Output\Get\Mapper $outputMapper;

    private IStageRule $stageRule;

    private ISeasonRepository $seasonRepository;

    public function __construct(IStageModel $stageModel,
                                Stage\Input\Upsert\Mapper $inputMapper,
                                Stage\Output\Get\Mapper $outputMapper,
                                IStageRule $stageRule,
                                ISeasonRepository $seasonRepository)
    {
        $this->stageModel = $stageModel;
        $this->inputMapper = $inputMapper;
        $this->outputMapper = $outputMapper;
        $this->stageRule = $stageRule;
        $this->seasonRepository = $seasonRepository;
    }

    public function process(IDto $inputDto): Stage\Output\Upsert\CollectionDto
    {
        $season = $this->seasonRepository->findById($inputDto->getSeasonId());

        if (!$season) {
            throw new NoSuchEntityException('Season: ' . $inputDto->getSeasonId());
        }

        $stageEntities = $this->inputMapper->map($inputDto);

        $this->stageRule->validate($season, $stageEntities);

        $stages = $this->stageModel
            ->setStages($stageEntities)
            ->withBlacklist()
            ->upsert()
            ->getStages();

        $outputDto = [];
        foreach ($stages as $stage) {
            $outputDto[] = $this->outputMapper->map($stage);
        }

        return new Stage\Output\Upsert\CollectionDto($outputDto);
    }
}