<?php


namespace Sportal\FootballApi\Application\PlayerStatistic\Service;


use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerSeasonStatisticBuilder;
use Sportal\FootballApi\Application\PlayerStatistic\Input;
use Sportal\FootballApi\Application\PlayerStatistic\Output;
use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerSeasonStatisticRepository;
use Sportal\FootballApi\Infrastructure\AOP\AttachAssets;
use Sportal\FootballApi\Infrastructure\PlayerStatistic\PlayerSeasonStatisticEntityResolver;
use Sportal\FootballApi\Domain\PlayerStatistic\PlayerSeasonStatisticFilter;

class Get implements IService
{

    private Output\Get\CollectionMapper $outputCollectionMapper;
    private IPlayerSeasonStatisticRepository $playerSeasonStatisticRepository;
    private IPlayerSeasonStatisticBuilder $playerSeasonStatisticBuilder;
    private PlayerSeasonStatisticEntityResolver $entityResolver;

    /**
     * @param Output\Get\CollectionMapper $outputCollectionMapper
     * @param IPlayerSeasonStatisticRepository $playerSeasonStatisticRepository
     * @param IPlayerSeasonStatisticBuilder $playerSeasonStatisticBuilder
     * @param PlayerSeasonStatisticEntityResolver $entityResolver
     */
    public function __construct(Output\Get\CollectionMapper $outputCollectionMapper,
                                IPlayerSeasonStatisticRepository $playerSeasonStatisticRepository,
                                IPlayerSeasonStatisticBuilder $playerSeasonStatisticBuilder,
                                PlayerSeasonStatisticEntityResolver $entityResolver) {
        $this->outputCollectionMapper = $outputCollectionMapper;
        $this->playerSeasonStatisticRepository = $playerSeasonStatisticRepository;
        $this->playerSeasonStatisticBuilder = $playerSeasonStatisticBuilder;
        $this->entityResolver = $entityResolver;
    }

    /**
     * @throws NoSuchEntityException
     * @AttachAssets
     */
    public function process(IDto $inputDto): Output\Get\SeasonStatisticDto
    {
        $filter = (new PlayerSeasonStatisticFilter())
            ->setPlayerIds($inputDto->getPlayerIds())
            ->setSeasonIds($inputDto->getSeasonIds())
            ->setTeamId($inputDto->getTeamId());

        $this->entityResolver->resolve($filter);

        $playerSeasonStatisticEntities = $this->playerSeasonStatisticRepository->getByFilter($filter);
        $collection = $this->playerSeasonStatisticBuilder->build($playerSeasonStatisticEntities);
        return $this->outputCollectionMapper->map($collection);
    }
}