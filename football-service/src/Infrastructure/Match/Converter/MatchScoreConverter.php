<?php


namespace Sportal\FootballApi\Infrastructure\Match\Converter;


use Sportal\FootballApi\Domain\Match\IMatchScore;
use Sportal\FootballApi\Domain\Match\IMatchScoreFactory;
use Sportal\FootballApi\Domain\Match\ITeamScore;
use Sportal\FootballApi\Domain\Match\ITeamScoreFactory;

class MatchScoreConverter
{
    const FIELD_TOTAL_HOME = 'goal_home';
    const FIELD_TOTAL_AWAY = 'goal_away';
    const FIELD_AGG_HOME = 'agg_home';
    const FIELD_AGG_AWAY = 'agg_away';
    const FIELD_HOME_SCORE = 'home_score';
    const FIELD_AWAY_SCORE = 'away_score';
    const FIELD_HALF_TIME = 'half_time';
    const FIELD_REGULAR_TIME = 'ordinary_time';
    const FIELD_EXTRA_TIME = 'extra_time';
    const FIELD_PENALTY_SHOOTOUT = 'penalty_shootout';
    const FIELD_HOME_HALF = self::FIELD_HOME_SCORE . self::FIELD_HALF_TIME;
    const FIELD_AWAY_HALF = self::FIELD_AWAY_SCORE . self::FIELD_HALF_TIME;
    const FIELD_HOME_REGULAR = self::FIELD_HOME_SCORE . self::FIELD_REGULAR_TIME;
    const FIELD_AWAY_REGULAR = self::FIELD_AWAY_SCORE . self::FIELD_REGULAR_TIME;
    const FIELD_HOME_EXTRA = self::FIELD_HOME_SCORE . self::FIELD_EXTRA_TIME;
    const FIELD_AWAY_EXTRA = self::FIELD_AWAY_SCORE . self::FIELD_EXTRA_TIME;
    const FIELD_HOME_PENALTY = self::FIELD_HOME_SCORE . self::FIELD_PENALTY_SHOOTOUT;
    const FIELD_AWAY_PENALTY = self::FIELD_AWAY_SCORE . self::FIELD_PENALTY_SHOOTOUT;

    private ITeamScoreFactory $teamScoreFactory;
    private IMatchScoreFactory $matchScoreFactory;

    /**
     * MatchScoreConverter constructor.
     * @param ITeamScoreFactory $teamScoreFactory
     * @param IMatchScoreFactory $matchScoreFactory
     */
    public function __construct(ITeamScoreFactory $teamScoreFactory, IMatchScoreFactory $matchScoreFactory)
    {
        $this->teamScoreFactory = $teamScoreFactory;
        $this->matchScoreFactory = $matchScoreFactory;
    }


    public function fromValue(array $data): ?IMatchScore
    {
        $homeScore = !empty($data[self::FIELD_HOME_SCORE]) ? json_decode($data[self::FIELD_HOME_SCORE], true) : [];
        $awayScore = !empty($data[self::FIELD_AWAY_SCORE]) ? json_decode($data[self::FIELD_AWAY_SCORE], true) : [];
        $homeAwayScores = array_merge(self::addPrefix(self::FIELD_HOME_SCORE, $homeScore), self::addPrefix(self::FIELD_AWAY_SCORE, $awayScore));
        $factory = $this->matchScoreFactory->setEmpty()
            ->setAggregate($this->createScore($data, self::FIELD_AGG_HOME, self::FIELD_AGG_AWAY))
            ->setHalfTime($this->createScore($homeAwayScores, self::FIELD_HOME_HALF, self::FIELD_AWAY_HALF))
            ->setRegularTime($this->createScore($homeAwayScores, self::FIELD_HOME_REGULAR, self::FIELD_AWAY_REGULAR))
            ->setExtraTime($this->createScore($homeAwayScores, self::FIELD_HOME_EXTRA, self::FIELD_AWAY_EXTRA))
            ->setPenaltyShootout($this->createScore($homeAwayScores, self::FIELD_HOME_PENALTY, self::FIELD_AWAY_PENALTY));

        $totalScore = $this->createScore($data, self::FIELD_TOTAL_HOME, self::FIELD_TOTAL_AWAY);
        if ($totalScore !== null) {
            return $factory->setTotal($totalScore)->create();
        } else {
            return null;
        }
    }

    public static function toValue(?IMatchScore $score): array
    {
        $data = [];
        $homeScore = [];
        $awayScore = [];
        if ($score !== null) {
            $data[self::FIELD_TOTAL_HOME] = $score->getTotal()->getHome();
            $data[self::FIELD_TOTAL_AWAY] = $score->getTotal()->getAway();

            if ($score->getAggregate() !== null) {
                $data[self::FIELD_AGG_HOME] = $score->getAggregate()->getHome();
                $data[self::FIELD_AGG_AWAY] = $score->getAggregate()->getAway();
            }
            if ($score->getHalfTime() !== null) {
                $homeScore[self::FIELD_HALF_TIME] = $score->getHalfTime()->getHome();
                $awayScore[self::FIELD_HALF_TIME] = $score->getHalfTime()->getAway();
            }
            if ($score->getRegularTime() !== null) {
                $homeScore[self::FIELD_REGULAR_TIME] = $score->getRegularTime()->getHome();
                $awayScore[self::FIELD_REGULAR_TIME] = $score->getRegularTime()->getAway();
            }
            if ($score->getExtraTime() !== null) {
                $homeScore[self::FIELD_EXTRA_TIME] = $score->getExtraTime()->getHome();
                $awayScore[self::FIELD_EXTRA_TIME] = $score->getExtraTime()->getAway();
            }
            if ($score->getPenaltyShootout() !== null) {
                $homeScore[self::FIELD_PENALTY_SHOOTOUT] = $score->getPenaltyShootout()->getHome();
                $awayScore[self::FIELD_PENALTY_SHOOTOUT] = $score->getPenaltyShootout()->getAway();
                $data["penalty_home"] = $score->getPenaltyShootout()->getHome();
                $data["penalty_away"] = $score->getPenaltyShootout()->getAway();
            }
        }

        if (!empty($homeScore) && !empty($awayScore)) {
            $data[self::FIELD_HOME_SCORE] = json_encode($homeScore);
            $data[self::FIELD_AWAY_SCORE] = json_encode($awayScore);
        } else {
            $data[self::FIELD_HOME_SCORE] = null;
            $data[self::FIELD_AWAY_SCORE] = null;
        }

        return $data;

    }

    private function createScore(array $scoreData, string $homeKey, string $awayKey): ?ITeamScore
    {
        if (isset($scoreData[$homeKey]) && isset($scoreData[$awayKey])) {
            return $this->teamScoreFactory->setEmpty()
                ->setHome($scoreData[$homeKey])
                ->setAway($scoreData[$awayKey])
                ->create();
        } else {
            return null;
        }
    }

    private static function addPrefix(string $prefix, array $array): array
    {
        return array_combine(
            array_map(fn($key) => $prefix . $key, array_keys($array)),
            $array
        );
    }

}