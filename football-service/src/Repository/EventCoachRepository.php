<?php
namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Cache\CacheManager;
use Doctrine\DBAL\Connection;
use Sportal\FootballApi\Model\EventCoach;

class EventCoachRepository extends Repository
{

    protected $coachRepository;

    public function __construct(Connection $conn, CacheManager $cacheManager, CoachRepository $coachRepository)
    {
        parent::__construct($conn, $cacheManager);
        $this->coachRepository = $coachRepository;
    }

    /**
     *
     * @param integer $eventId
     * @param boolean $homeTeam
     * @return EventCoach
     */
    public function findByEvent($eventId, $homeTeam)
    {
        return $this->find([
            'event_id' => $eventId,
            'home_team' => $homeTeam
        ]);
    }

    public function find($id)
    {
        return $this->getByPk($this->getModelClass(),
            [
                'event_id' => $id['event_id'],
                'home_team' => (int) $id['home_team']
            ], [
                $this,
                'buildObject'
            ], $this->getJoin());
    }

    public function findAll($filter = [])
    {
        // TODO Auto-generated method stub
    }

    public function getModelClass()
    {
        return EventCoach::class;
    }

    public function createObject(array $eventCoachArr)
    {
        $eventCoach = new EventCoach();
        $eventCoach->setEventId($eventCoachArr['event_id'])
            ->setHomeTeam($eventCoachArr['home_team'])
            ->setCoachName($eventCoachArr['coach_name']);
        if (isset($eventCoachArr['coach'])) {
            $eventCoach->setCoach($eventCoachArr['coach']);
        }
        return $eventCoach;
    }

    public function buildObject(array $eventCoach)
    {
        if (isset($eventCoach['coach'])) {
            $eventCoach['coach'] = $this->coachRepository->createPartialObject($eventCoach['coach']);
        }
        return $this->createObject($eventCoach);
    }

    public function getJoin()
    {
        static $columns = null;
        if ($columns === null) {
            $columns = [
                [
                    'className' => $this->coachRepository->getModelClass(),
                    'type' => 'left',
                    'columns' => $this->coachRepository->getColumns()
                ]
            ];
        }
        return $columns;
    }
}