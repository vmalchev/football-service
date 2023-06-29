<?php


namespace Sportal\FootballApi\Application\Stage\Service;


use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\Stage\Input\ListAll\Dto;
use Sportal\FootballApi\Application\Stage\Output\Get\Mapper;
use Sportal\FootballApi\Application\Stage\Output\Upsert\CollectionDto;
use Sportal\FootballApi\Domain\Season\ISeasonRepository;
use Sportal\FootballApi\Domain\Stage\IStageRepository;
use Sportal\FootballApi\Domain\Stage\StageStatusCalculator;

class ListAll implements IService
{

    private IStageRepository $stageRepository;

    private ISeasonRepository $seasonRepository;

    private Mapper $mapper;

    private StageStatusCalculator $statusCalculator;

    public function __construct(IStageRepository $stageRepository,
                                Mapper $mapper,
                                ISeasonRepository $seasonRepository,
                                StageStatusCalculator $statusCalculator)
    {
        $this->stageRepository = $stageRepository;
        $this->mapper = $mapper;
        $this->seasonRepository = $seasonRepository;
        $this->statusCalculator = $statusCalculator;
    }

    /**
     * @throws NoSuchEntityException
     */
    public function process(IDto $inputDto): CollectionDto
    {
        /**
         * @var Dto $inputDto
         */
        if (!$this->seasonRepository->exists($inputDto->getSeasonId())) {
            throw new NoSuchEntityException('Season: ' . $inputDto->getSeasonId());
        }

        $stages = $this->statusCalculator->applyStatuses($this->stageRepository->findBySeasonId($inputDto->getSeasonId()));

        $stageDto = [];
        foreach ($stages as $stage) {
            $stageDto[] = $this->mapper->map($stage);
        }

        return new CollectionDto($stageDto);
    }

}