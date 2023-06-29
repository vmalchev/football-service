<?php


namespace Sportal\FootballApi\Domain\TeamStatistic;


use Sportal\FootballApi\Model\Event;
use Sportal\FootballApi\Model\EventStatus;

class TeamStatisticAggregator
{
    /**
     * @param Event[] $matches
     * @param int $teamId
     */
    public function aggregateTeamStatistic(array $matches, $teamId): TeamStatisticAggregatorDto
    {
        $teamStatisticDtoBuilder = new TeamStatisticDtoBuilder();

        foreach ($matches as $match) {
            $goalScored = null;
            $goalConceded = null;
            if ($teamId == $match->getHomeId()) {
                $goalScored = $match->getGoalHome();
                $goalConceded = $match->getGoalAway();
            } elseif($teamId == $match->getAwayId()) {
                $goalScored = $match->getGoalAway();
                $goalConceded = $match->getGoalHome();
            }
            if ($goalConceded !== null && $goalConceded !== null
                && in_array($match->getEventStatus()->getType(), [EventStatus::TYPE_FINISHED, EventStatus::TYPE_INPROGRESS])) {
                $teamStatisticDtoBuilder->played++;
                $teamStatisticDtoBuilder->goalsScored += $goalScored;
                $teamStatisticDtoBuilder->goalsConceded += $goalConceded;
                if ($goalScored > $goalConceded) {
                    $teamStatisticDtoBuilder->win++;
                } else if ($goalScored < $goalConceded) {
                    $teamStatisticDtoBuilder->defeats++;
                } else {
                    $teamStatisticDtoBuilder->draw++;
                }
            }
        }

        return $teamStatisticDtoBuilder->build();
    }
}