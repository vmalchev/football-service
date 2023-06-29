<?php
namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Repository\Repository;
use Doctrine\DBAL\Connection;
use Sportal\FootballApi\Cache\CacheManager;
use Sportal\FootballApi\Model\EventTeamStats;

class EventTeamStatsRepository extends Repository
{

    private $teamRepository;

    private $mlContent;

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::__construct()
     */
    public function __construct(Connection $conn, CacheManager $cacheManager, TeamRepository $teamRepository,
        MlContentRepository $mlContent)
    {
        // TODO Auto-generated method stub
        parent::__construct($conn, $cacheManager);
        $this->teamRepository = $teamRepository;
        $this->mlContent = $mlContent;
    }

    /**
     *
     * @param array $data
     * @return \Sportal\FootballApi\Model\EventTeamStats
     */
    public function createObject(array $data)
    {
        $teamStats = (new EventTeamStats())->setId($data['id'])
            ->setEventId($data['event_id'])
            ->setHomeTeam($data['home_team'])
            ->setTeamName($data['team_name'])
            ->setStatistics(
            (is_array($data['statistics']) ? $data['statistics'] : json_decode($data['statistics'], true)));
        if (isset($data['team'])) {
            $teamStats->setTeam($data['team']);
        }
        
        if (isset($data['updated_at'])) {
            $teamStats->setUpdatedAt(new \DateTime($data['updated_at']));
        }
        
        return $teamStats;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::find()
     * @return \Sportal\FootballApi\Model\EventTeamStats
     */
    public function find($id)
    {
        return $this->getByPk($this->getModelClass(), [
            'id' => $id
        ], [
            $this,
            'buildObject'
        ], $this->getJoin());
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::findAll()
     * @return \Sportal\FootballApi\Model\EventTeamStats[]
     */
    public function findAll($filter = [])
    {
        return $this->getAll($this->getModelClass(), $filter, [
            $this,
            'buildObject'
        ], $this->getJoin(),
            [
                [
                    'key' => 'home_team',
                    'direction' => 'desc'
                ]
            ]);
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::getModelClass()
     */
    public function getModelClass()
    {
        return EventTeamStats::class;
    }

    public function findUpdatedAfter(\DateTime $updatedAfter)
    {
        return $this->queryPersistance(
            [
                [
                    'key' => 'updated_at',
                    'sign' => '>=',
                    'value' => static::formatTime($updatedAfter)
                ]
            ], [
                $this,
                'buildObject'
            ], $this->getJoin());
    }

    /**
     *
     * @param integer $eventId
     * @param string $langCode
     * @return \Sportal\FootballApi\Model\EventTeamStats[]
     */
    public function findByEvent($eventId, $langCode = null)
    {
        $results = $this->findAll([
            'event_id' => $eventId
        ]);
        if ($langCode !== null) {
            foreach ($results as $teamStat) {
                if ($teamStat->getTeam() !== null) {
                    $this->mlContent->setContent($teamStat->getTeam(), $langCode);
                }
            }
        }
        return $results;
    }

    protected function buildObject(array $eventPlayerArr)
    {
        if ($eventPlayerArr['team_id'] !== null) {
            $eventPlayerArr['team'] = $this->teamRepository->createPartial($eventPlayerArr['team']);
        } else {
            unset($eventPlayerArr['team']);
        }
        return $this->createObject($eventPlayerArr);
    }

    protected function getJoin()
    {
        return [
            [
                'className' => $this->teamRepository->getModelClass(),
                'type' => 'left',
                'columns' => $this->teamRepository->getColumns()
            ]
        ];
    }
}