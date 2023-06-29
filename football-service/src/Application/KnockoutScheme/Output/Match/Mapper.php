<?php


namespace Sportal\FootballApi\Application\KnockoutScheme\Output\Match;


use Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutMatchEntity;

class Mapper
{
    private \Sportal\FootballApi\Application\Match\Output\Get\Score\Mapper $scoreMapper;

    /**
     * Mapper constructor.
     * @param \Sportal\FootballApi\Application\Match\Output\Get\Score\Mapper $scoreMapper
     */
    public function __construct(\Sportal\FootballApi\Application\Match\Output\Get\Score\Mapper $scoreMapper)
    {
        $this->scoreMapper = $scoreMapper;
    }


    public function map(IKnockoutMatchEntity $matchEntity): MatchDto
    {
        return new MatchDto($matchEntity->getId(), $matchEntity->getKickOffTime(), $this->scoreMapper->map($matchEntity->getScore()),
            $matchEntity->getHomeTeamId(), $matchEntity->getAwayTeamId());
    }
}