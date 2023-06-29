<?php


namespace Sportal\FootballApi\Infrastructure\KnockoutScheme;


use Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutMatchEntity;
use Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutMatchEntityFactory;
use Sportal\FootballApi\Infrastructure\Match\Converter\MatchScoreArrayConverter;

class MatchMapper
{
    private MatchScoreArrayConverter $scoreConverter;
    private IKnockoutMatchEntityFactory $matchFactory;

    /**
     * MatchMapper constructor.
     * @param MatchScoreArrayConverter $scoreConverter
     * @param IKnockoutMatchEntityFactory $matchFactory
     */
    public function __construct(MatchScoreArrayConverter $scoreConverter, IKnockoutMatchEntityFactory $matchFactory)
    {
        $this->scoreConverter = $scoreConverter;
        $this->matchFactory = $matchFactory;
    }

    public function map(array $match): IKnockoutMatchEntity
    {
        return $this->matchFactory->setEmpty()
            ->setId($match['id'])
            ->setKickoffTime($match['kickoff_time'])
            ->setHomeTeamId($match['home_team_id'])
            ->setAwayTeamId($match['away_team_id'])
            ->setScore($this->scoreConverter->convert($match))
            ->create();
    }
}