<?php


namespace Sportal\FootballApi\Application\Standing\Input\Put\TopScorer;


use Sportal\FootballApi\Domain\Standing\ITopScorerEntry;
use Sportal\FootballApi\Domain\Standing\ITopScorerEntryFactory;

class Mapper
{
    private ITopScorerEntryFactory $factory;

    /**
     * Mapper constructor.
     * @param ITopScorerEntryFactory $factory
     */
    public function __construct(ITopScorerEntryFactory $factory)
    {
        $this->factory = $factory;
    }


    public function map(Dto $dto): ITopScorerEntry
    {
        return $this->factory->setEmpty()
            ->setTeamId($dto->getTeamId())
            ->setPlayerId($dto->getPlayerId())
            ->setRank($dto->getRank())
            ->setGoals($dto->getGoals())
            ->setAssists($dto->getAssists())
            ->setPlayed($dto->getPlayed())
            ->setMinutes($dto->getMinutes())
            ->setPenalties($dto->getPenalties())
            ->setScoredFirst($dto->getScoredFirst())
            ->setYellowCards($dto->getYellowCards())
            ->setYellowCards($dto->getYellowCards())
            ->setRedCards($dto->getRedCards())
            ->create();
    }
}