<?php


namespace Sportal\FootballApi\Application\Standing\Input\Put\League;


use Sportal\FootballApi\Domain\Standing\ILeagueStandingEntry;
use Sportal\FootballApi\Domain\Standing\ILeagueStandingEntryFactory;

class Mapper
{
    private ILeagueStandingEntryFactory $factory;

    /**
     * Mapper constructor.
     * @param ILeagueStandingEntryFactory $factory
     */
    public function __construct(ILeagueStandingEntryFactory $factory)
    {
        $this->factory = $factory;
    }

    public function map(Dto $dto): ILeagueStandingEntry
    {
        return $this->factory->setEmpty()
            ->setTeamId($dto->getTeamId())
            ->setRank($dto->getRank())
            ->setPlayed($dto->getPlayed())
            ->setWins($dto->getWins())
            ->setDraws($dto->getDraws())
            ->setLosses($dto->getLosses())
            ->setPoints($dto->getPoints())
            ->setGoalsFor($dto->getGoalsFor())
            ->setGoalsAgainst($dto->getGoalsAgainst())
            ->create();
    }

}