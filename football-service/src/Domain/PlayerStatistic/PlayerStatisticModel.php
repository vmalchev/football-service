<?php


namespace Sportal\FootballApi\Domain\PlayerStatistic;


final class PlayerStatisticModel implements IPlayerStatisticModel
{
    private IPlayerStatisticEntity $playerStatisticEntity;

    private IPlayerStatisticRepository $playerStatisticRepository;

    /**
     * @param IPlayerStatisticRepository $playerStatisticRepository
     */
    public function __construct(IPlayerStatisticRepository $playerStatisticRepository)
    {
        $this->playerStatisticRepository = $playerStatisticRepository;
    }

    /**
     * @return IPlayerStatisticModel
     */
    public function create(): IPlayerStatisticModel
    {
        $this->playerStatisticRepository->insert($this->getPlayerStatisticEntity());
        return $this;
    }

    /**
     * @return IPlayerStatisticModel
     */
    public function update(): IPlayerStatisticModel
    {
        $this->playerStatisticRepository->update($this->getPlayerStatisticEntity());
        return $this;
    }

    /**
     * @return IPlayerStatisticEntity
     */
    public function getPlayerStatisticEntity(): IPlayerStatisticEntity
    {
        return $this->playerStatisticEntity;
    }

    /**
     * @param IPlayerStatisticEntity $playerStatisticEntity
     */
    public function setPlayerStatisticEntity(IPlayerStatisticEntity $playerStatisticEntity): void
    {
        $this->playerStatisticEntity = $playerStatisticEntity;
    }
}