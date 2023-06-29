<?php


namespace Sportal\FootballApi\Application\Stage\Input\Upsert;


use Sportal\FootballApi\Domain\Match\MatchCoverage;
use Sportal\FootballApi\Domain\Season\ISeasonRepository;
use Sportal\FootballApi\Domain\Stage\IStageEntityFactory;
use Sportal\FootballApi\Domain\Stage\StageType;
use Sportal\FootballApi\Infrastructure\Database\Converter\DateConverter;

class Mapper
{

    private IStageEntityFactory $entityFactory;

    private ISeasonRepository $seasonRepository;

    public function __construct(IStageEntityFactory $entityFactory,
                                ISeasonRepository $seasonRepository)
    {
        $this->entityFactory = $entityFactory;
        $this->seasonRepository = $seasonRepository;
    }

    public function map(CollectionDto $collectionDto): array
    {
        $stageDtos = $collectionDto->getStages();
        $season = $this->seasonRepository->findById($collectionDto->getSeasonId());

        $stageEntities = [];
        foreach ($stageDtos as $dto) {
            $stageEntities[] = $this->entityFactory
                ->setId($dto->getId())
                ->setSeasonId($collectionDto->getSeasonId())
                ->setSeason($season)
                ->setType(StageType::{$dto->getType()}())
                ->setName($dto->getName())
                ->setStartDate(DateConverter::fromValue($dto->getStartDate()))
                ->setEndDate(DateConverter::fromValue($dto->getEndDate()))
                ->setCoverage(MatchCoverage::forKey($dto->getCoverage()))
                ->setOrderInSeason($dto->getOrderInSeason())
                ->create();
        }

        return $stageEntities;
    }

}