<?php

namespace Sportal\FootballApi\Builder;

use Sportal\FootballApi\Database\Query\CompositeExpression;
use Sportal\FootballApi\Database\Query\Expression;
use Sportal\FootballApi\Database\Query\Filter;
use Sportal\FootballApi\Filter\MatchListingFilter;
use Sportal\FootballApi\Filter\StageFilter;
use Sportal\FootballApi\Model\TournamentOrder;
use Sportal\FootballApi\Repository\EventStatusRepository;
use Sportal\FootballApi\Repository\TournamentSeasonStageRepository;

class MatchExpressionBuilder
{
    /**
     * @var MatchListingFilter
     */
    private $filter;

    /**
     * @var CompositeExpression
     */
    private $expression;

    private TournamentSeasonStageRepository $stageRepository;

    private EventStatusRepository $eventStatusRepository;

    /**
     * @param MatchListingFilter $filter
     * @param CompositeExpression $expression
     */
    public function __construct(
        MatchListingFilter $filter,
        CompositeExpression $expression,
        TournamentSeasonStageRepository $stageRepository,
        EventStatusRepository $eventStatusRepository
    )
    {
        $this->filter = $filter;
        $this->expression = $expression;
        $this->stageRepository = $stageRepository;
        $this->eventStatusRepository = $eventStatusRepository;
    }

    public function build(): Expression
    {
        $buildMap = [
            $this->getMatchExpression(),
            $this->getTeamExpression(),
            $this->getFromStartTimeExpression(),
            $this->getToStartTimeExpression(),
            $this->getUpdatedTimeExpression(),
            $this->getStatusTypesExpression(),
            $this->getRoundExpression(),
            $this->getStageExpression(),
            $this->getTournamentOrderExpresion(),
            $this->getVenueExpression(),
            $this->getRefereeExpression()
        ];

        $queryExpression = $this->expression->setType(CompositeExpression::TYPE_AND);

        foreach ($buildMap as $expression) {
            if ($expression !== null) {
                $queryExpression->add($expression);
            }
        }

        return $queryExpression;
    }

    private function getFromStartTimeExpression()
    {
        if ($this->filter->getFromStartTime()) {
            return new Filter(
                'start_time',
                $this->filter->getFromStartTime()->setTimezone(new \DateTimeZone('UTC'))->format("Y-m-d H:i:s"),
                '>='
            );
        }

        return null;
    }

    private function getToStartTimeExpression()
    {
        if ($this->filter->getToStartTime()) {
            return new Filter(
                'start_time',
                $this->filter->getToStartTime()->setTimezone(new \DateTimeZone('UTC'))->format("Y-m-d H:i:s"),
                '<='
            );
        }

        return null;
    }

    private function getUpdatedTimeExpression()
    {
        if ($this->filter->getUpdatedTime()) {
            return new Filter(
                'updated_at',
                $this->filter->getUpdatedTime()->setTimezone(new \DateTimeZone('UTC'))->format("Y-m-d H:i:s"),
                '>='
            );
        }

        return null;
    }

    private function getTournamentOrderExpresion()
    {
        if ($this->filter->getTournamentOrder()) {
            return new Filter(
                TournamentOrder::CLIENT_INDEX,
                $this->filter->getTournamentOrder(),
                Filter::TYPE_EQ,
                TournamentOrder::class
            );
        }

        return null;
    }

    private function getStatusTypesExpression()
    {
        if (!empty($this->filter->getStatusTypes())) {
            $allStatuses = $this->eventStatusRepository->findAll();
            $statusIds = [];
            foreach ($allStatuses as $status) {
                if (in_array($status->getType(), $this->filter->getStatusTypes())) {
                    $statusIds[] = $status->getId();
                }
            }
            return new Filter('event_status_id', $statusIds, Filter::TYPE_IN);
        } else if (!empty($this->filter->getStatusCode())) {
            $allStatuses = $this->eventStatusRepository->findAll();
            $statusId = null;
            foreach ($allStatuses as $status) {
                if ($status->getCode() == $this->filter->getStatusCode()) {
                  $statusId = $status->getId();
                  break;
                }
            }
            return new Filter('event_status_id', $statusId, Filter::TYPE_EQ);
        } else {
            return null;
        }
    }

    private function getMatchExpression()
    {
        if (count($this->filter->getMatchIds())) {
            return new Filter('id', $this->filter->getMatchIds(), Filter::TYPE_IN);
        }

        return null;
    }

    private function getRoundExpression()
    {
        if (count($this->filter->getRounds())) {
            return new Filter('round', $this->filter->getRounds(), Filter::TYPE_IN);
        }

        return null;
    }

    private function getTeamExpression()
    {
        if (!is_array($this->filter->getTeamIds()) && !is_array($this->filter->getTeamNames())) {
            return null;
        }

        if (count($this->filter->getTeamNames()) == 1) {
            return (clone $this->expression->setType(CompositeExpression::TYPE_OR))
                ->in('home_name', $this->filter->getTeamNames())
                ->in('away_name', $this->filter->getTeamNames());
        } elseif (count($this->filter->getTeamNames()) > 1) {
            return (clone $this->expression->setType(CompositeExpression::TYPE_OR))
                ->add(
                    (new CompositeExpression(CompositeExpression::TYPE_AND))
                        ->in('home_name', $this->filter->getTeamNames())
                        ->in('away_name', $this->filter->getTeamNames())
                );
        }


        if (count($this->filter->getTeamIds()) == 1) {
            return (clone $this->expression->setType(CompositeExpression::TYPE_OR))
                ->in('home_id', $this->filter->getTeamIds())
                ->in('away_id', $this->filter->getTeamIds());
        } elseif (count($this->filter->getTeamIds()) > 1) {
            return (clone $this->expression->setType(CompositeExpression::TYPE_AND))
                ->in('home_id', $this->filter->getTeamIds())
                ->in('away_id', $this->filter->getTeamIds());
        }

        return null;
    }


    public function getStageExpression()
    {
        if (count($this->filter->getStageIds())) {
            return new Filter('tournament_season_stage_id', $this->filter->getStageIds(), Filter::TYPE_IN);
        } else {
            $stageFilter = new StageFilter();
            $stageFilter->setSeasonIds($this->filter->getSeasonIds());
            $stageFilter->setTournamentIds($this->filter->getTournamentIds());
            $stageFilter->setTournamentOrder($this->filter->getTournamentOrder());
            if (!$stageFilter->isEmpty()) {
                $stageIds = $this->stageRepository->findStageIds($stageFilter);
                return new Filter('tournament_season_stage_id', (!empty($stageIds)) ? $stageIds : [null], Filter::TYPE_IN);
            } else {
                return null;
            }
        }

    }

    private function getRefereeExpression(): ?Filter
    {
        if (!empty($this->filter->getRefereeId())) {
            return new Filter('referee_id', $this->filter->getRefereeId(), Filter::TYPE_EQ);
        } else {
            return null;
        }
    }

    private function getVenueExpression(): ?Filter
    {
        if (!empty($this->filter->getVenueId())) {
            return new Filter('venue_id', $this->filter->getVenueId(), Filter::TYPE_EQ);
        } else {
            return null;
        }
    }


}
