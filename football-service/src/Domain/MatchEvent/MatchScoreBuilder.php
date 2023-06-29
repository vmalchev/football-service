<?php


namespace Sportal\FootballApi\Domain\MatchEvent;

use Sportal\FootballApi\Domain\Match\ITeamScore;
use Sportal\FootballApi\Domain\Match\ITeamScoreFactory;

class MatchScoreBuilder implements IMatchScoreBuilder, IMatchScoreBuilderFactory
{
    private ITeamScoreFactory $teamScoreFactory;

    private ITeamScore $totalScore;

    /**
     * MatchEventScoreModel constructor.
     * @param ITeamScoreFactory $teamScoreFactory
     */
    public function __construct(ITeamScoreFactory $teamScoreFactory)
    {
        $this->teamScoreFactory = $teamScoreFactory;
    }

    public function create(): IMatchScoreBuilder
    {
        $scoreBuilder = clone $this;
        $scoreBuilder->totalScore = $this->teamScoreFactory->setEmpty()->setHome(0)->setAway(0)->create();
        return $scoreBuilder;
    }

    public function addEvent(IMatchEventEntity $event): void
    {
        if (MatchEventType::isGoal($event->getEventType())) {
            if ($event->getTeamPosition()->equals(TeamPositionStatus::HOME())) {
                $this->totalScore = $this->teamScoreFactory->setEmpty()
                    ->setHome($this->totalScore->getHome() + 1)
                    ->setAway($this->totalScore->getAway())
                    ->create();
            } else {
                $this->totalScore = $this->teamScoreFactory->setEmpty()
                    ->setHome($this->totalScore->getHome())
                    ->setAway($this->totalScore->getAway() + 1)
                    ->create();
            }
        }
    }

    public function getTotalScore(): ITeamScore
    {
        return $this->totalScore;
    }
}