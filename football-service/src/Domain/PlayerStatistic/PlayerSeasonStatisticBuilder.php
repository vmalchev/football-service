<?php


namespace Sportal\FootballApi\Domain\PlayerStatistic;


use Sportal\FootballApi\Domain\Player\IPlayerRepository;
use Sportal\FootballApi\Domain\Season\ISeasonRepository;
use Sportal\FootballApi\Domain\Team\ITeamRepository;

final class PlayerSeasonStatisticBuilder implements IPlayerSeasonStatisticBuilder
{

    /**
     * @var IPlayerRepository
     */
    private IPlayerRepository $playerRepository;

    /**
     * @var ITeamRepository
     */
    private ITeamRepository $teamRepository;

    /**
     * @var ISeasonRepository
     */
    private ISeasonRepository $seasonRepository;

    /**
     * @var IPlayerSeasonStatisticEntityFactory
     */
    private IPlayerSeasonStatisticEntityFactory $playerSeasonStatisticEntityFactory;

    /**
     * @var IPlayerSeasonStatisticCollection
     */
    private IPlayerSeasonStatisticCollection $playerSeasonStatisticCollection;

    /**
     * @param IPlayerRepository $playerRepository
     * @param ITeamRepository $teamRepository
     * @param ISeasonRepository $seasonRepository
     * @param IPlayerSeasonStatisticEntityFactory $playerSeasonStatisticEntityFactory
     * @param IPlayerSeasonStatisticCollection $playerSeasonStatisticCollection
     */
    public function __construct(
        IPlayerRepository $playerRepository,
        ITeamRepository $teamRepository,
        ISeasonRepository $seasonRepository,
        IPlayerSeasonStatisticEntityFactory $playerSeasonStatisticEntityFactory,
        IPlayerSeasonStatisticCollection $playerSeasonStatisticCollection
    )
    {
        $this->playerRepository = $playerRepository;
        $this->teamRepository = $teamRepository;
        $this->seasonRepository = $seasonRepository;
        $this->playerSeasonStatisticEntityFactory = $playerSeasonStatisticEntityFactory;
        $this->playerSeasonStatisticCollection = $playerSeasonStatisticCollection;
    }

    /**
     * @param IPlayerSeasonStatisticEntity[] $playerSeasonStatisticEntities
     */
    public function build(array $playerSeasonStatisticEntities): IPlayerSeasonStatisticCollection
    {
        $playerIds = array_map(fn(IPlayerSeasonStatisticEntity $entity) => $entity->getPlayerId(), $playerSeasonStatisticEntities);
        $seasonIds = array_map(fn(IPlayerSeasonStatisticEntity $entity) => $entity->getSeasonId(), $playerSeasonStatisticEntities);
        $teamIds = array_merge(...array_map(fn(IPlayerSeasonStatisticEntity $entity) => $entity->getTeamIds(), $playerSeasonStatisticEntities));

        $playerCollection = $this->playerRepository->findByIds($playerIds);
        $seasonCollection = $this->seasonRepository->findByIds($seasonIds);
        $teamCollection = $this->teamRepository->findByIds(array_unique($teamIds));

        $entities = [];
        foreach ($playerSeasonStatisticEntities as $entity) {
            $factory = $this->playerSeasonStatisticEntityFactory->setEntity($entity);

            $playerEntity = $playerCollection->getById($entity->getPlayerId());
            $seasonEntity = $seasonCollection->getById($entity->getSeasonId());
            $teamEntities = $teamCollection->getByIds($entity->getTeamIds());

            $factory->setPlayerEntity($playerEntity);
            $factory->setTeamEntities($teamEntities);
            $factory->setSeasonEntity($seasonEntity);

            $entities[] = $factory->create();
        }

        $this->playerSeasonStatisticCollection->setCollection($entities);
        return $this->playerSeasonStatisticCollection;
    }
}