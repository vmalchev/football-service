<?php


namespace Sportal\FootballApi\Infrastructure\Match\Converter;


use Sportal\FootballApi\Domain\Match\IMatchScore;
use Sportal\FootballApi\Domain\Match\IMatchScoreFactory;
use Sportal\FootballApi\Domain\Match\ITeamScore;
use Sportal\FootballApi\Domain\Match\ITeamScoreFactory;

class MatchScoreArrayConverter
{
    private IMatchScoreFactory $scoreFactory;

    private ITeamScoreFactory $teamScoreFactory;

    /**
     * MatchScoreArrayConverter constructor.
     * @param IMatchScoreFactory $scoreFactory
     * @param ITeamScoreFactory $teamScoreFactory
     */
    public function __construct(IMatchScoreFactory $scoreFactory, ITeamScoreFactory $teamScoreFactory)
    {
        $this->scoreFactory = $scoreFactory;
        $this->teamScoreFactory = $teamScoreFactory;
    }


    public function convert(array $scores): ?IMatchScore
    {
        $totalScore = $this->convertTeamScore($scores['total'] ?? null);
        if ($totalScore !== null) {
            return $this->scoreFactory->setEmpty()
                ->setTotal($totalScore)
                ->setHalfTime($this->convertTeamScore($scores['half_time'] ?? null))
                ->setRegularTime($this->convertTeamScore($scores['regular_time'] ?? null))
                ->setExtraTime($this->convertTeamScore($scores['extra_time'] ?? null))
                ->setPenaltyShootout($this->convertTeamScore($scores['penalty_shootout'] ?? null))
                ->setAggregate($this->convertTeamScore($scores['aggregate']))
                ->create();
        } else {
            return null;
        }
    }

    private function convertTeamScore(?array $teamScore): ?ITeamScore
    {
        if (!empty($teamScore) && isset($teamScore['home']) && isset($teamScore['away'])) {
            return $this->teamScoreFactory->setEmpty()
                ->setHome($teamScore['home'])
                ->setAway($teamScore['away'])->create();
        } else {
            return null;
        }
    }
}