<?php


namespace Sportal\FootballApi\Domain\Match;


use Sportal\FootballApi\Domain\MatchStatus\IMatchStatusRepository;
use Sportal\FootballApi\Domain\Stage\IStageRepository;
use Sportal\FootballApi\Domain\Stage\StageFilter;
use Sportal\FootballApi\Domain\TournamentOrder\ITournamentOrderRepository;
use Sportal\FootballApi\Infrastructure\Database\Query\CompositeExpression;
use Sportal\FootballApi\Infrastructure\Database\Query\Expression;
use Sportal\FootballApi\Infrastructure\Database\Query\Filter;
use Sportal\FootballApi\Infrastructure\Match\MatchTableMapper;

class MatchExpressionBuilder
{

    private IMatchStatusRepository $matchStatusRepository;

    private IStageRepository $stageRepository;

    private ITournamentOrderRepository $tournamentOrderRepository;

    /**
     * MatchExpressionBuilder constructor.
     * @param IMatchStatusRepository $matchStatusRepository
     * @param IStageRepository $stageRepository
     * @param ITournamentOrderRepository $tournamentOrderRepository
     */
    public function __construct(IMatchStatusRepository $matchStatusRepository,
                                IStageRepository $stageRepository,
                                ITournamentOrderRepository $tournamentOrderRepository)
    {
        $this->matchStatusRepository = $matchStatusRepository;
        $this->stageRepository = $stageRepository;
        $this->tournamentOrderRepository = $tournamentOrderRepository;
    }

    public function build(MatchFilter $filter): Expression
    {
        $buildMap = [
            $this->getMatchesExpression($filter),
            $this->getStagesExpression($filter),
            $this->getGroupIdsExpression($filter),
            $this->getRoundIdsExpression($filter),
            $this->getFromKickoffTimeExpression($filter),
            $this->getToKickoffTimeExpression($filter),
            $this->getTeamIdsExpression($filter),
            $this->getStatusesExpression($filter),
            $this->getRefereeIdExpression($filter),
            $this->getVenueIdExpression($filter),
            $this->getRoundFilterExpression($filter)
        ];

        $queryExpression = new CompositeExpression(CompositeExpression::TYPE_AND);

        foreach ($buildMap as $expression) {
            if ($expression !== null) {
                $queryExpression->add($expression);
            }
        }

        return $queryExpression;
    }

    private function getMatchesExpression(MatchFilter $filter): ?Expression
    {
        if (!empty($filter->getMatchIds())) {
            return new Filter(MatchTableMapper::FIELD_ID, $filter->getMatchIds(), Filter::TYPE_IN);
        }

        return null;
    }

    private function getStagesExpression(MatchFilter $filter): ?Expression
    {
        if (!empty($filter->getStageIds())) {
            return new Filter(MatchTableMapper::FIELD_STAGE_ID, $filter->getStageIds(), Filter::TYPE_IN);
        } else if (!empty($filter->getSeasonIds()) || !empty($filter->getTournamentIds())) {
            $stages = $this->stageRepository->findByFilter(StageFilter::create()
                ->setSeasonIds($filter->getSeasonIds())
                ->setTournamentIds($filter->getTournamentIds()));
            $stageIds = array_map(fn($stage) => $stage->getId(), $stages);
            return new Filter(MatchTableMapper::FIELD_STAGE_ID, empty($stageIds) ? [null] : $stageIds, Filter::TYPE_IN);
        } else if (!is_null($filter->getTournamentGroup())) {
            $tournaments = $this->tournamentOrderRepository->findByClientCode($filter->getTournamentGroup());
            $tournamentIds = array_map(fn($tournament) => $tournament->getTournamentId(), $tournaments);

            $stages = $this->stageRepository->findByFilter(StageFilter::create()
                ->setTournamentIds($tournamentIds));
            $stageIds = array_map(fn($stage) => $stage->getId(), $stages);
            return new Filter(MatchTableMapper::FIELD_STAGE_ID, empty($stageIds) ? [null] : $stageIds, Filter::TYPE_IN);
        }

        return null;
    }

    private function getGroupIdsExpression(MatchFilter $filter): ?Expression
    {
        if (!is_null($filter->getGroupIds()) && count($filter->getGroupIds())) {
            return new Filter(MatchTableMapper::FIELD_GROUP_ID, $filter->getGroupIds(), Filter::TYPE_IN);
        }

        return null;
    }

    private function getRoundIdsExpression(MatchFilter $filter): ?Expression
    {
        if (!is_null($filter->getRoundIds()) && count($filter->getRoundIds())) {
            return new Filter(MatchTableMapper::FIELD_ROUND_TYPE_ID, $filter->getRoundIds(), Filter::TYPE_IN);
        }

        return null;
    }

    private function getRoundFilterExpression(MatchFilter $filter): ?Expression
    {
        if (!empty($filter->getRoundFilter())) {
            $expression = new CompositeExpression(CompositeExpression::TYPE_OR);
            foreach ($filter->getRoundFilter() as $roundFilter) {
                $expression->add((new CompositeExpression(CompositeExpression::TYPE_AND))
                    ->eq(MatchTableMapper::FIELD_ROUND_TYPE_ID, $roundFilter->getRoundId())
                    ->eq(MatchTableMapper::FIELD_STAGE_ID, $roundFilter->getStageId()));
            }
            return $expression;
        }

        return null;
    }

    private function getFromKickoffTimeExpression(MatchFilter $filter): ?Expression
    {
        if ($filter->getFromKickoffTime()) {
            return new Filter(
                MatchTableMapper::FIELD_KICKOFF_TIME,
                $filter->getFromKickoffTime()->setTimezone(new \DateTimeZone('UTC'))->format("Y-m-d H:i:s"),
                '>='
            );
        }

        return null;
    }

    private function getToKickoffTimeExpression(MatchFilter $filter): ?Expression
    {
        if ($filter->getToKickoffTime()) {
            return new Filter(
                MatchTableMapper::FIELD_KICKOFF_TIME,
                $filter->getToKickoffTime()->setTimezone(new \DateTimeZone('UTC'))->format("Y-m-d H:i:s"),
                '<='
            );
        }

        return null;
    }

    private function getTeamIdsExpression(MatchFilter $filter): ?Expression
    {
        if (!is_null($filter->getTeamIds())) {
            if (count($filter->getTeamIds()) == 1) {
                return (new CompositeExpression(CompositeExpression::TYPE_OR))
                    ->in(MatchTableMapper::FIELD_HOME_TEAM_ID, $filter->getTeamIds())
                    ->in(MatchTableMapper::FIELD_AWAY_TEAM_ID, $filter->getTeamIds());
            } elseif (count($filter->getTeamIds()) > 1) {
                return (new CompositeExpression(CompositeExpression::TYPE_AND))
                    ->in(MatchTableMapper::FIELD_HOME_TEAM_ID, $filter->getTeamIds())
                    ->in(MatchTableMapper::FIELD_AWAY_TEAM_ID, $filter->getTeamIds());
            }
        }

        return null;
    }

    private function getStatusesExpression(MatchFilter $filter): ?Expression
    {
        $statusCodes = $this->getStatusCodesExpression($filter);
        if (!is_null($statusCodes)) {
            return $statusCodes;
        }

        if (!is_null($filter->getStatusTypes()) && count($filter->getStatusTypes())) {
            $statuses = $this->matchStatusRepository->findByStatusTypes($filter->getStatusTypes());
            $statusIds = array_map(fn($status) => $status->getId(), $statuses);

            return new Filter(MatchTableMapper::FIELD_STATUS_ID, $statusIds, Filter::TYPE_IN);
        }

        return null;
    }

    private function getStatusCodesExpression(MatchFilter $filter): ?Expression
    {
        if (!is_null($filter->getStatusCodes()) && count($filter->getStatusCodes())) {
            $statuses = $this->matchStatusRepository->findByStatusCodes($filter->getStatusCodes());
            $statusIds = array_map(fn($status) => $status->getId(), $statuses);

            return new Filter(MatchTableMapper::FIELD_STATUS_ID, $statusIds, Filter::TYPE_IN);
        }

        return null;
    }

    private function getRefereeIdExpression(MatchFilter $filter): ?Expression
    {
        if ($filter->getRefereeId()) {
            return new Filter(MatchTableMapper::FIELD_REFEREE_ID, $filter->getRefereeId(), Filter::TYPE_EQ);
        }

        return null;
    }

    private function getVenueIdExpression(MatchFilter $filter): ?Expression
    {
        if ($filter->getVenueId()) {
            return new Filter(MatchTableMapper::FIELD_VENUE_ID, $filter->getVenueId(), Filter::TYPE_EQ);
        }

        return null;
    }

}