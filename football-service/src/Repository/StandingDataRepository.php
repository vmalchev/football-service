<?php
namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Repository\Repository;
use Sportal\FootballApi\Model\StandingData;
use Doctrine\DBAL\Connection;
use Sportal\FootballApi\Cache\CacheManager;
use Sportal\FootballApi\Model\StandingDataRule;
use Sportal\FootballApi\Model\Standing;
use Sportal\FootballApi\Model\SurrogateKeyInterface;
use Sportal\FootballApi\Model\TournamentSeasonStage;

/**
 * @author kstoilov
 *
 */
class StandingDataRepository extends Repository
{

    protected $playerRepository;

    protected $teamRepository;

    protected $standingDataRules;

    protected $standingRepository;

    protected $teamFormRepository;

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::__construct()
     */
    public function __construct(Connection $conn, CacheManager $cacheManager, PlayerRepository $playerRepository,
        TeamRepository $teamRepository, StandingDataRuleRepository $standingDataRules,
        StandingRepository $standingRepository, TeamFormRepository $teamFormRepository)
    {
        parent::__construct($conn, $cacheManager);
        $this->playerRepository = $playerRepository;
        $this->teamRepository = $teamRepository;
        $this->standingDataRules = $standingDataRules;
        $this->standingRepository = $standingRepository;
        $this->teamFormRepository = $teamFormRepository;
    }

    /**
     *
     * @param array $data
     * @return \Sportal\FootballApi\Model\StandingData
     */
    public function createObject(array $data)
    {
        $object = new StandingData();
        $object->setId($data['id'])
            ->setStandingId($data['standing_id'])
            ->setRank($data['rank'])
            ->setTeam($data['team'])
            ->setData(is_array($data['data']) ? $data['data'] : json_decode($data['data'], true));
        if (isset($data['player'])) {
            $object->setPlayer($data['player']);
        }
        
        return $object;
    }

    public function buildObject(array $data)
    {
        $data['team'] = $this->teamRepository->createPartial($data['team']);
        if (isset($data['player'])) {
            $data['player'] = $this->playerRepository->createPartialObject($data['player']);
        }
        return $this->createObject($data);
    }

    public function findByStandingId($standingId, $addRules = false)
    {
        $results = $this->findAll([
            'standing_id' => $standingId
        ]);
        if (count($results) > 0 && $addRules) {}
        return $results;
    }

    public function findStanding(SurrogateKeyInterface $league, $type = Standing::TYPE_LEAGUE)
    {
        $standing = $this->standingRepository->findByModelType($league, $type);
        if ($standing !== null) {
            $results = $this->findByStandingId($standing->getId());
            return $results;
        }
    }

    /**
     *
     * @param StandingData[] $standingTable
     */
    public function addLeagueStandingRules(array $standingTable)
    {
        if (count($standingTable) > 0) {
            $rules = $this->standingDataRules->findByStanding($standingTable[0]->getStandingId());
            foreach ($rules as $rule) {
                $index = $rule->getRank() - 1;
                if (isset($standingTable[$index])) {
                    $standingTable[$index]->addStandingRule($rule->getStandingRule());
                }
            }
        }
    }

    /**
     *
     * @param StandingData[] $standingTable
     * @param integer $tournamentSeasonStageId TournamentSeasonStage id for which to include form
     */
    public function addTeamForm(array $standingTable, $tournamentSeasonStageId, $showEvent = false)
    {
        $teamForm = $this->teamFormRepository->findByStage($tournamentSeasonStageId);
        if ($teamForm !== null && count($teamForm) > 0) {
            foreach ($standingTable as $standingData) {
                $team = $standingData->getTeam();
                if (isset($teamForm[$team->getId()])) {
                    foreach ($teamForm[$team->getId()] as $form) {
                        $form->setShowEvent($showEvent);
                    }
                    $team->setFormGuide($teamForm[$team->getId()]);
                }
            }
        }
    }

    public function findTeamStanding(SurrogateKeyInterface $league, $teamId)
    {
        $standing = $this->findLeagueStanding($league);
        if (! empty($standing)) {
            foreach ($standing as $standingData) {
                if ($standingData->getTeam()->getId() == $teamId) {
                    return [
                        $standingData
                    ];
                }
            }
        }
        return null;
    }

    public function findLeagueStanding(SurrogateKeyInterface $league)
    {
        $standingTable = $this->findStanding($league);
        if (!empty($standingTable)) {
            return $standingTable;
        }
        return null;
    }

    /**
     *
     * @param integer[] $standingIds
     * @return \Sportal\FootballApi\Model\StandingData[]
     */
    public function findByStandingIds(array $standingIds)
    {
        if (count($standingIds) > 0) {
            return $this->findAll(
                [
                    [
                        'key' => 'standing_id',
                        'sign' => 'in',
                        'value' => $standingIds
                    ]
                ]);
        }
        return [];
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::find()
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
     * @see \Sportal\FootballApi\Repository\Repository::findAll()
     * @return \Sportal\FootballApi\Model\StandingData[]
     */
    public function findAll($filter = [])
    {
        $results = $this->queryTable($this->getPersistanceName($this->getModelClass()), $filter,
            [
                $this,
                'buildObject'
            ], $this->getJoin(), [
                [
                    'key' => 'rank'
                ]
            ]);
        
        return $results;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::getModelClass()
     */
    public function getModelClass()
    {
        return StandingData::class;
    }

    protected function getJoin()
    {
        return [
            [
                'className' => $this->playerRepository->getModelClass(),
                'type' => 'left',
                'columns' => $this->playerRepository->getPartialColumns()
            ],
            [
                'className' => $this->teamRepository->getModelClass(),
                'type' => 'inner',
                'columns' => $this->teamRepository->getPartialColumns()
            ]
        ];
    }

    /**
     * @param $stageId
     * @param null $teamId
     * @return StandingData[]
     */
    public function findByEntity($entityClass, $entityId, $teamId=null)
    {
        $join = $this->getJoin();
        $join[] =
            [
                'className' => Standing::class,
                'type' => 'inner',
                'columns' => []
            ];
        $filter =  [
            [
                'key' => 'entity_id',
                'sign' => '=',
                'value' => $entityId,
                'table' => $this->getPersistanceName(Standing::class),
            ],
            [
                'key' => 'entity',
                'sign' => '=',
                'value' => $this->getPersistanceName($entityClass),
                'table' => $this->getPersistanceName(Standing::class),
            ],
            [
                'key' => 'type',
                'sign' => '=',
                'value' => Standing::TYPE_LEAGUE,
                'table' => $this->getPersistanceName(Standing::class),
            ]
        ];

        if (! empty($teamId)) {
            $filter[] = [
                'key' => 'team_id',
                'sign' => '=',
                'value' => $teamId
            ];
        }
        return $this->queryTable($this->getPersistanceName($this->getModelClass()), $filter,
            [
                $this,
                'buildObject'
            ], $join, [
                [
                    'key' => 'rank'
                ]
            ]);
    }
}