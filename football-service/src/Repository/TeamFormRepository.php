<?php
namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Cache\CacheManager;
use Sportal\FootballApi\Model\TeamForm;
use Sportal\FootballApi\Model\Event;
use Sportal\FootballApi\Model\PartialTeam;

class TeamFormRepository
{

    const MAX_RESULTS = 5;

    const TEAM_FORM_KEY = 'team_form';

    const STAGE_FORM_KEY = 'tournament_season_stage_form';

    protected $cacheManager;

    protected $eventRepository;

    protected $stageRepository;

    public function __construct(CacheManager $cacheManager, EventRepository $eventRepository, TournamentSeasonStageRepository $stageRepository)
    {
        $this->cacheManager = $cacheManager;
        $this->eventRepository = $eventRepository;
        $this->stageRepository = $stageRepository;
    }

    public function addEvent($teamId, Event $event)
    {
        $stageId = $event->getTournamentSeasonStage()->getId();
        $new = $this->createObject($teamId, $event);

        $teamForm = $this->cacheManager->getInstance(static::TEAM_FORM_KEY, [
            $teamId
        ]);
        if ($teamForm !== null) {
            $teamForm = $this->addTo($teamForm, $new);
            $this->cacheManager->setInstance(static::TEAM_FORM_KEY, [
                $teamId
            ], $teamForm);
        }

        $stageForm = $this->cacheManager->getInstance(static::STAGE_FORM_KEY, [
            $stageId
        ]);
        if ($stageForm !== null) {
            $stageForm[$teamId] = isset($stageForm[$teamId]) ? $this->addTo($stageForm[$teamId], $new) : [
                $new
            ];
            $this->cacheManager->setInstance(static::STAGE_FORM_KEY, [
                $stageId
            ], $stageForm);
        }
    }

    /**
     *
     * @param integer $teamId
     * @return TeamForm[]
     */
    public function findByTeam($teamId)
    {
        $key = [
            $teamId
        ];
        $instance = $this->cacheManager->getInstance(static::TEAM_FORM_KEY, $key);

        if ($instance === null) {
            $instance = $this->cacheManager->populateInstance(static::TEAM_FORM_KEY, $key, function () use ($teamId) {
                return $this->getTeamForm($teamId);
            });
        }

        return $instance;
    }

    public function setTeamForm(PartialTeam $team, $showEvent = false)
    {
        if ($team !== null) {
            $form = $this->findByTeam($team->getId());
            if ($showEvent) {
                foreach ($form as $teamForm) {
                    $teamForm->setShowEvent($showEvent);
                }
            }
            $team->setFormGuide($form);
            return $form;
        }
    }

    /**
     *
     * @param integer $tournamentSeasonStageId
     * @return array
     */
    public function findByStage($tournamentSeasonStageId)
    {
        $key = [
            $tournamentSeasonStageId
        ];
        $instance = $this->cacheManager->getInstance(static::STAGE_FORM_KEY, $key);
        if ($instance === null) {
            $instance = $this->cacheManager->populateInstance(static::STAGE_FORM_KEY, $key,
                function () use ($tournamentSeasonStageId) {
                    return $this->getStageForm($tournamentSeasonStageId);
                });
        }

        return $instance;
    }

    public function createObject($teamId, Event $event)
    {
        $event->setVenue(null)->setReferee(null);
        return (new TeamForm())->setTeamId($teamId)->setEvent($event->clonePartial());
    }

    protected function addTo(array $form, TeamForm $new)
    {
        foreach ($form as $index => $current) {
            if ($current->getEvent()->getId() == $new->getEvent()->getId()) {
                $form[$index] = $new;

                return $form;
            }
        }

        $form[] = $new;
        usort($form,
            function (TeamForm $a, TeamForm $b) {
                $bTime = $b->getEvent()
                    ->getStartTime()
                    ->getTimestamp();
                $aTime = $a->getEvent()
                    ->getStartTime()
                    ->getTimestamp();
                return $bTime - $aTime;
            });
        return array_slice($form, 0, static::MAX_RESULTS);
    }

    protected function getStageForm($stageId)
    {
        $teams = $this->stageRepository->getTeamsByStage($stageId);
        $form = [];
        foreach ($teams as $team) {
            $form[$team->getId()] = $this->getTeamForm($team->getId(), $stageId);
        }
        return $form;
    }

    protected function getTeamForm($teamId, $stageId = null)
    {
        $events = $this->eventRepository->getTeamForm($teamId, $stageId);
        $form = [];
        foreach ($events as $event) {
            $form[] = $this->createObject($teamId, $event);
        }
        return $form;
    }
}