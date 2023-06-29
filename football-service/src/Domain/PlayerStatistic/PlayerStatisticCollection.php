<?php


namespace Sportal\FootballApi\Domain\PlayerStatistic;


final class PlayerStatisticCollection implements IPlayerStatisticCollection
{
    private IPlayerStatisticBuilder $playerStatisticBuilder;

    private IPlayerStatisticRepository $playerStatisticRepository;

    private IPlayerStatisticEntityFactory $playerStatisticFactory;

    /**
     * @var IPlayerStatisticModel[]
     */
    private array $playerStatisticModels;

    /**
     * @param IPlayerStatisticBuilder $playerStatisticBuilder
     * @param IPlayerStatisticRepository $playerStatisticRepository
     * @param IPlayerStatisticEntityFactory $playerStatisticFactory
     */
    public function __construct(
        IPlayerStatisticBuilder $playerStatisticBuilder,
        IPlayerStatisticRepository $playerStatisticRepository,
        IPlayerStatisticEntityFactory $playerStatisticFactory
    ) {
        $this->playerStatisticBuilder = $playerStatisticBuilder;
        $this->playerStatisticRepository = $playerStatisticRepository;
        $this->playerStatisticFactory = $playerStatisticFactory;
    }

    /**
     * @param IPlayerStatisticEntity[] $playerStatisticEntities
     * @return IPlayerStatisticCollection
     */
    public function upsert(array $playerStatisticEntities): IPlayerStatisticCollection
    {
        foreach ($playerStatisticEntities as $playerStatisticEntity) {
            $entity = $this->playerStatisticRepository->find($playerStatisticEntity);

            if ($entity !== null) {
                $this->add(
                    $this->playerStatisticBuilder
                        ->build(
                            $this->playerStatisticFactory
                                ->setEntity($playerStatisticEntity)
                                ->create()
                        )->update()
                );
            } else {
                $this->add($this->playerStatisticBuilder->build($playerStatisticEntity)->create());
            }
        }

        return $this;
    }

    /**
     * @param IPlayerStatisticModel $playerStatisticModel
     */
    public function add(IPlayerStatisticModel $playerStatisticModel)
    {
        $this->playerStatisticModels[] = $playerStatisticModel;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->playerStatisticModels);
    }
}