<?php
namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Repository\Repository;
use Doctrine\DBAL\Connection;
use Sportal\FootballApi\Cache\CacheManager;
use Sportal\FootballApi\Model\PlayerStatistics;
use Sportal\FootballApi\Model\SurrogateKeyInterface;
use Sportal\FootballApi\Model\ModelInterface;
use Sportal\FootballApi\Util\NameUtil;
use Sportal\FootballApi\Model\TournamentSeason;

/**
 * @author kstoilov
 *
 */
class PlayerStatisticsRepository extends Repository
{

    private $teamRepository;

    private $playerRepository;

    private $seasonRepository;

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::__construct()
     */
    public function __construct(Connection $conn, CacheManager $cacheManager, TeamRepository $teamRepository,
        PlayerRepository $playerRepository, TournamentSeasonRepository $seasonRepository)
    {
        parent::__construct($conn, $cacheManager);
        $this->teamRepository = $teamRepository;
        $this->playerRepository = $playerRepository;
        $this->seasonRepository = $seasonRepository;
    }

    public function createObject(array $data)
    {
        $playerStat = new PlayerStatistics();
        $playerStat->setTournament($data['tournament'])
            ->setPlayer($data['player'])
            ->setTeam($data['team'])
            ->setPosition($data['position'] ?? null)
            ->setShirtNumber($data['shirt_number'])
            ->setStatistics(
            is_array($data['statistics']) ? $data['statistics'] : json_decode($data['statistics'], true));

        return $playerStat;
    }

    /**
     *
     * @param integer $teamId
     * @return integer[]
     */
    public function findSeasonsIdsByTeam($teamId)
    {
        $seasonName = $this->getPersistanceName($this->seasonRepository->getModelClass());
        $qb = $this->conn->createQueryBuilder();
        $qb->select('DISTINCT(tournament_entity_id)')->from('player_statistics');
        $where = $qb->expr()->andX();
        $where->add('tournament_entity = ' . $qb->createPositionalParameter($seasonName));
        $where->add('team_id = ' . $qb->createPositionalParameter($teamId));
        $qb->where($where);
        return $qb->execute()->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function findByTournament(SurrogateKeyInterface $tournament)
    {
        $callback = function ($arr) use ($tournament) {
            $arr['tournament'] = $tournament;
            return $this->buildObject($arr);
        };
        
        return $this->queryPersistance(
            [
                'tournament_entity' => $this->getPersistanceName($tournament),
                'tournament_entity_id' => $tournament->getId()
            ], $callback, $this->getJoin());
    }

    public function findTeamSeasonStatistics($teamId, $seasonId = null)
    {
        $filter = [
            'team_id' => $teamId
        ];

        if (! empty($seasonId)) {
            $filter['tournament_entity_id'] = $seasonId;
        }

        $join = $this->seasonJoin();
        $join['player']['columns'] = $this->playerRepository->getColumns();
        $join['player']['join'] = $this->playerRepository->getJoin();
        $expand = [
            'player' => true
        ];

        $buildObject = function ($data) use ($expand) {
            return $this->buildObject($data, $expand);
        };

        return $this->findSeasonStatistics($filter, $buildObject, $join);
    }

    public function patchExisting(ModelInterface $existing, ModelInterface $updated)
    {
        $existing->setStatistics($updated->getStatistics());
        if ($existing->getShirtNumber() === null && $updated->getShirtNumber() !== null) {
            $existing->setShirtNumber($updated->getShirtNumber());
        }
        return $existing;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::getChangedKeys()
     */
    public function getChangedKeys(ModelInterface $existing, ModelInterface $updated)
    {
        $changed = [];
        if ($existing->getStatistics() != $updated->getStatistics()) {
            $changed[] = 'statistics';
        }
        
        if ($existing->getShirtNumber() === null && $updated->getShirtNumber() !== null) {
            $changed[] = 'shirt_number';
        }
        
        return $changed;
    }

    /**
     *
     * @param unknown $playerId
     * @param unknown $national
     * @return PlayerStatistics[]
     */
    public function findPlayerSeasonStatistics($playerId, $national = null)
    {
        $filter = [
            'player_id' => $playerId
        ];
        
        if ($national !== null) {
            $filter[] = [
                'table' => $this->getPersistanceName($this->teamRepository->getModelClass()),
                'key' => 'national',
                'sign' => '=',
                'value' => (int) $national
            ];
        }
        
        $buildObject = [
            $this,
            'buildObject'
        ];

        return $this->findSeasonStatistics($filter, $buildObject, $this->seasonJoin());
    }

    /**
     * @see \Sportal\FootballApi\Repository\Repository::find()
     * @return \Sportal\FootballApi\Model\PlayerStatistics
     */
    public function find($id)
    {
        return $this->getByPk($this->getModelClass(), $id, [
            $this,
            'buildObject'
        ], $this->getJoin());
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::findAll()
     * @return \Sportal\FootballApi\Model\PlayerStatistics[]
     */
    public function findAll($filter = [])
    {
        return $this->getAll($this->getModelClass(), $filter, [
            $this,
            'buildObject'
        ], $this->getJoin());
    }

    /**
     *
     * @param ModelInterface[] $created
     * @param ModelInterface[] $updated
     */
    public function saveModels(array $created, array $updated)
    {
        $table = $this->getPersistanceName($this->getModelClass());
        
        $this->conn->transactional(
            function () use ($created, $updated, $table) {
                foreach ($created as $model) {
                    $this->conn->insert($table, $model->getPersistanceMap());
                }
                foreach ($updated as $model) {
                    $this->conn->update($table, $model->getPersistanceMap(), $model->getPrimaryKeyMap());
                }
            });
    }

    /**
     *
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::refreshCache()
     * @param PlayerStatistics[] $createdModels
     */
    public function refreshCache(array $changedKeys, array $createdModels)
    {
        $persistanceName = $this->getPersistanceName($this->getModelClass());
        foreach ($this->cacheManager->getParameters($persistanceName) as $parameters) {
            foreach ($createdModels as $model) {
                if ($this->shouldRefresh($model, $parameters)) {
                    $this->cacheManager->refreshList($persistanceName, $parameters,
                        function () use ($parameters) {
                            return $this->getListPKs($parameters);
                        });
                    break;
                }
            }
        }
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::getModelClass()
     */
    public function getModelClass()
    {
        return PlayerStatistics::class;
    }

    protected function getPrimaryKeys()
    {
        return [
            'player_id',
            'team_id',
            'tournament_entity',
            'tournament_entity_id'
        ];
    }

    protected function seasonJoin()
    {
        $join = $this->getJoin();
        $join['season'] = [
            'className' => $this->seasonRepository->getModelClass(),
            'type' => 'inner',
            'columns' => $this->seasonRepository->getColumns(),
            'key' => 'tournament_entity_id',
            'join' => $this->seasonRepository->getJoin()
        ];
        return $join;
    }

    protected function getJoin()
    {
        return [
            'player' => [
                'className' => $this->playerRepository->getModelClass(),
                'type' => 'inner',
                'columns' => $this->playerRepository->getColumns()
            ],
            'team' => [
                'className' => $this->teamRepository->getModelClass(),
                'type' => 'inner',
                'columns' => $this->teamRepository->getColumns()
            ]
        ];
    }

    protected function findSeasonStatistics(array $filter, callable $buildObject, array $join)
    {
        $seasonName = $this->getPersistanceName($this->seasonRepository->getModelClass());

        $order = [
            [
                'key' => 'name',
                'object' => $seasonName,
                'direction' => 'DESC'
            ],
            [
                'key' => 'tournament_id',
                'object' => $seasonName
            ],
            [
                'key' => 'shirt_number'
            ]
        ];

        $filter = array_merge($filter, [
            'tournament_entity' => $seasonName
        ]);

        return $this->queryPersistance($filter, $buildObject, $join, $order);
    }

    protected function buildObject(array $data, array $expand = null)
    {
        $data['team'] = $this->teamRepository->createPartial($data['team']);
        $data['position'] = $data['player']['position'] ?? null;

        if ($expand !== null && ! empty($expand['player'])) {
            $data['player'] = $this->playerRepository->buildObject($data['player']);
        } else {
            $data['player'] = $this->playerRepository->createPartialObject($data['player']);
        }

        $seasonName = $this->getPersistanceName($this->seasonRepository->getModelClass());

        if (! isset($data['tournament']) && $data['tournament_entity'] == $seasonName) {
            $data['tournament'] = $this->seasonRepository->createObject($data[$seasonName]);
        }

        return $this->createObject($data);
    }

    private function shouldRefresh(PlayerStatistics $model, array $parameters)
    {
        $map = $model->getPersistanceMap();
        
        foreach ($map as $key => $value) {
            if ($key == 'tournament_entity' || $key == 'tournament_entity_id') {
                if (static::isKeyRelevant($parameters, 'tournament_entity', $map['tournament_entity']) &&
                     static::isKeyRelevant($parameters, 'tournament_entity_id', $map['tournament_entity_id'])) {
                    return true;
                }
            } elseif (static::isKeyRelevant($parameters, $key, $value)) {
                return true;
            }
        }
        
        return false;
    }
}