<?php

namespace Sportal\FootballApi\Domain\Season;

use Sportal\FootballApi\Domain\Round\IRoundRepository;
use Sportal\FootballApi\Domain\Round\RoundFilterFactory;
use Sportal\FootballApi\Domain\Round\RoundStatusCalculator;
use Sportal\FootballApi\Domain\Stage\IStageRepository;
use Sportal\FootballApi\Domain\Stage\StageStatusCalculator;

class SeasonDetailsBuilder implements ISeasonDetailsBuilder
{

    private SeasonResolver $seasonResolver;

    private IStageRepository $stageRepository;

    private StageStatusCalculator $stageStatusCalculator;

    private IRoundRepository $roundRepository;

    private RoundFilterFactory $roundFilterFactory;

    private RoundStatusCalculator $roundStatusCalculator;

    private ISeasonDetails $seasonDetails;

    /**
     * @param SeasonResolver $seasonResolver
     * @param IStageRepository $stageRepository
     * @param StageStatusCalculator $stageStatusCalculator
     * @param IRoundRepository $roundRepository
     * @param RoundFilterFactory $roundFilterFactory
     * @param RoundStatusCalculator $roundStatusCalculator
     * @param ISeasonDetails $seasonDetails
     */
    public function __construct(SeasonResolver $seasonResolver,
                                IStageRepository $stageRepository,
                                StageStatusCalculator $stageStatusCalculator,
                                IRoundRepository $roundRepository,
                                RoundFilterFactory $roundFilterFactory,
                                RoundStatusCalculator $roundStatusCalculator,
                                ISeasonDetails $seasonDetails)
    {
        $this->seasonResolver = $seasonResolver;
        $this->stageRepository = $stageRepository;
        $this->stageStatusCalculator = $stageStatusCalculator;
        $this->roundRepository = $roundRepository;
        $this->roundFilterFactory = $roundFilterFactory;
        $this->roundStatusCalculator = $roundStatusCalculator;
        $this->seasonDetails = $seasonDetails;
    }

    public function build(SeasonFilter $filter): ISeasonDetails
    {
        $seasonEntity = $this->seasonResolver->getSeasonEntity($filter);

        $stageEntities = $this->stageStatusCalculator->applyStatuses($this->stageRepository->findBySeasonId($seasonEntity->getId()));

        $roundEntities = [];
        foreach ($stageEntities as $stageEntity) {
            $rounds = $this->roundStatusCalculator->applyStatuses($this->roundRepository->findAll(
                $this->roundFilterFactory->setStageId($stageEntity->getId())->create()
            ));

            $roundEntities[$stageEntity->getId()] = $rounds;
        }

        return $this->seasonDetails->setSeason($seasonEntity)->setStages($stageEntities)->setRounds($roundEntities);
    }
}