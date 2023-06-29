<?php

namespace Sportal\FootballApi\Application\Round;

use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\Round\Output\ICollectionDto;
use Sportal\FootballApi\Application\Round\Input\Mapper;
use Sportal\FootballApi\Domain\Round\IRoundRepository;
use Sportal\FootballApi\Domain\Round\RoundStatusCalculator;
use Sportal\FootballApi\Domain\Season\ISeasonRepository;
use Sportal\FootballApi\Domain\Stage\IStageRepository;

class Get implements IService
{

    private IRoundRepository $roundRepository;

    private ISeasonRepository $seasonRepository;

    private IStageRepository $stageRepository;

    private Mapper $inputMapper;

    private Output\ListAll\Mapper $outputMapper;

    private RoundStatusCalculator $statusCalculator;

    /**
     * @param IRoundRepository $roundRepository
     * @param ISeasonRepository $seasonRepository
     * @param IStageRepository $stageRepository
     * @param Mapper $inputMapper
     * @param Output\ListAll\Mapper $outputMapper
     * @param RoundStatusCalculator $statusCalculator
     */
    public function __construct(IRoundRepository $roundRepository,
                                ISeasonRepository $seasonRepository,
                                IStageRepository $stageRepository,
                                Mapper $inputMapper,
                                Output\ListAll\Mapper $outputMapper,
                                RoundStatusCalculator $statusCalculator)
    {
        $this->roundRepository = $roundRepository;
        $this->seasonRepository = $seasonRepository;
        $this->stageRepository = $stageRepository;
        $this->inputMapper = $inputMapper;
        $this->outputMapper = $outputMapper;
        $this->statusCalculator = $statusCalculator;
    }


    /**
     * @throws NoSuchEntityException
     */
    public function process(IDto $inputDto): ICollectionDto {

        /** @var Input\Dto $inputDto */
        if (!is_null($inputDto->getSeasonId())) {
            if (!$this->seasonRepository->exists($inputDto->getSeasonId())) {
                throw new NoSuchEntityException("Season with id " . $inputDto->getSeasonId() . " does not exist.");
            } else {
                return $this->outputMapper->map($this->roundRepository->findAll($this->inputMapper->map($inputDto)));
            }
        } else {
            if (!$this->stageRepository->exists($inputDto->getStageId())) {
                throw new NoSuchEntityException("Stage with id " . $inputDto->getStageId() . " does not exist.");
            } else {
                return $this->outputMapper->map(
                    $this->statusCalculator->applyStatuses($this->roundRepository->findAll($this->inputMapper->map($inputDto)))
                );
            }
        }
    }
}