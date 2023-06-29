<?php
namespace Sportal\FootballApi\Import;

use Sportal\FootballApi\Repository\EventCoachRepository;
use Sportal\FootballApi\Repository\MappingRepositoryContainer;
use Psr\Log\LoggerInterface;
use Sportal\FootballFeedCommon\EventPlayerFeedInterface;
use Sportal\FootballApi\Model\Event;
use Sportal\FootballApi\Model\EventCoach;

class EventCoachImporter extends Importer
{

    /**
     * @var EventCoachRepository
     */
    protected $repository;

    protected $feed;

    protected $coachImporter;

    public function __construct(EventCoachRepository $repository, MappingRepositoryContainer $mappings,
        LoggerInterface $logger, EventPlayerFeedInterface $feed, CoachImporter $coachImporter)
    {
        parent::__construct($repository, $mappings, $logger);
        $this->feed = $feed;
        $this->coachImporter = $coachImporter;
    }

    public function import($eventId, $sourceName = null)
    {
        $feedId = $this->mappings->get($sourceName)->getRemoteId(Event::class, $eventId);
        if ($feedId !== null) {
            $coaches = $this->feed->getEventCoach($feedId);
            foreach ($coaches as $coachData) {
                $eventCoachArr = [
                    'event_id' => $eventId,
                    'home_team' => $coachData['home_team'],
                    'coach_name' => $coachData['coach_name']
                ];
                if (isset($coachData['coach_id'])) {
                    $eventCoachArr['coach'] = $this->coachImporter->getOrImport($coachData['coach_id'], true,
                        $sourceName);
                }
                $model = $this->repository->createObject($eventCoachArr);
                $this->importMatching($model,
                    function (EventCoach $model) {
                        return $this->repository->findByEvent($model->getEventId(), $model->getHomeTeam());
                    });
            }
        }
    }
}