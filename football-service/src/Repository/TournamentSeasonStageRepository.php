<?php

namespace Sportal\FootballApi\Repository;

use Doctrine\DBAL\Connection;
use Sportal\FootballApi\Cache\CacheManager;
use Sportal\FootballApi\Filter\StageFilter;
use Sportal\FootballApi\Model\ModelInterface;
use Sportal\FootballApi\Model\PartialStage;
use Sportal\FootballApi\Model\TournamentSeasonStage;

class TournamentSeasonStageRepository extends Repository
{

    protected $countryRepository;

    protected $teamRepository;

    protected $seasonRepository;

    public function __construct(Connection                 $conn, CacheManager $cacheManager, CountryRepository $countryRepository, TeamRepository $teamRepository,
                                TournamentSeasonRepository $seasonRepository)
    {
        parent::__construct($conn, $cacheManager);
        $this->countryRepository = $countryRepository;
        $this->teamRepository = $teamRepository;
        $this->seasonRepository = $seasonRepository;
    }

    public function cloneObject(PartialStage $stage)
    {
        $obj = new TournamentSeasonStage();
        $stage->cloneInto($obj);
        return $obj;
    }

    public function createPartial(array $stageArr)
    {
        $stage = new PartialStage();
        $this->setStageInfo($stage, $stageArr);
        return $stage;
    }

    /**
     *
     * @param array $stageArr
     * @return \Sportal\FootballApi\Model\TournamentSeasonStage
     */
    public function createObject($stageArr)
    {
        $stage = new TournamentSeasonStage();
        $this->setStageInfo($stage, $stageArr);

        if (isset($stageArr['live'])) {
            $stage->setLive($stageArr['live']);
        }

        if (isset($stageArr['start_date'])) {
            $stage->setStartDate(new \DateTime($stageArr['start_date']));
        }

        if (isset($stageArr['end_date'])) {
            $stage->setEndDate(new \DateTime($stageArr['end_date']));
        }

        if (isset($stageArr['updated_at'])) {
            $stage->setUpdatedAt(new \DateTime($stageArr['updated_at']));
        }
        if (isset($stageArr['qualification'])) {
            $stage->setQualification($stageArr['qualification']);
        }
        if (isset($stageArr['stage_groups'])) {
            $stage->setStageGroups($stageArr['stage_groups']);
        }
        if (isset($stageArr['order_in_season'])) {
            $stage->setOrderInSeason($stageArr['order_in_season']);
        }

        return $stage;
    }

    /**
     * @param ModelInterface $existing
     * @param ModelInterface $updated
     */
    public function patchExisting(ModelInterface $existing, ModelInterface $updated)
    {
        if ($updated->getTournamentSeasonId() !== null) {
            $existing->setTournamentSeasonId($updated->getTournamentSeasonId());
        }

        if ($updated->getConfederation() !== null) {
            $existing->setConfederation($updated->getConfederation());
        }

        if ($updated->getStartDate() !== null) {
            $existing->setStartDate($updated->getStartDate());
        }
        if ($updated->getEndDate() !== null) {
            $existing->setEndDate($updated->getEndDate());
        }
        if ($updated->getCup() !== null) {
            $existing->setCup($updated->getCup());
        }
        if ($updated->getQualification() !== null) {
            $existing->setQualification($updated->getQualification());
        }
        if ($updated->getLive() !== null) {
            $existing->setLive($updated->getLive());
        }
        if ($updated->getCountry() !== null) {
            $existing->setCountry($updated->getCountry());
        }
        if ($updated->getStageGroups() !== null) {
            $existing->setStageGroups($updated->getStageGroups());
        }
        if ($updated->getOrderInSeason() !== null) {
            $existing->setOrderInSeason($updated->getOrderInSeason());
        }
        if ($updated->getType() !== null) {
            $existing->setType($updated->getType());
        }
        return $existing;
    }

    protected function getIgnoredKeys()
    {
        return array_merge(parent::getIgnoredKeys(), [
            'name'
        ]);
    }

    /**
     *
     * {@inheritDoc}
     * @return TournamentSeasonStage
     * @see \Sportal\FootballApi\Repository\Repository::find()
     */
    public function find($id)
    {
        return $this->getByPk(TournamentSeasonStage::class, array(
            'id' => $id
        ), [
            $this,
            'buildObject'
        ], $this->getJoin());
    }

    public function findById($id): ?TournamentSeasonStage
    {
        $tableName = $this->getPersistanceName($this->getModelClass());

        $data = $this->queryTable(
            $tableName,
            ['id' => $id],
            [$this, 'buildObject'],
            $this->getJoin()
        );

        return empty($data) ? null : $data[0];
    }

    public function findByTournamentSeason($id, $langCode = null)
    {
        $filter = [
            'tournament_season_id' => $id
        ];

        return $this->queryPersistance($filter, [
            $this,
            'buildObject'
        ], $this->getJoin(), [
            [
                'key' => 'order_in_season'
            ],
            [
                'key' => 'id'
            ]
        ]);
    }

    /**
     * Get stages which are currently active
     * @return \Sportal\FootballApi\Model\TournamentSeasonStage[]
     */
    public function findActive()
    {
        $currentDate = gmdate('Y-m-d');
        return $this->findAll(
            [
                [
                    'key' => 'start_date',
                    'sign' => '<=',
                    'value' => $currentDate
                ],
                [
                    'key' => 'end_date',
                    'sign' => '>=',
                    'value' => $currentDate
                ]
            ]);
    }

    public function findExisting(TournamentSeasonStage $match)
    {
        $filter = [
            'tournament_season_id' => $match->getTournamentSeasonId()
        ];
        if ($match->getCup() !== null) {
            $filter['cup'] = (int)$match->getCup();
        }
        $stages = $this->queryPersistance($filter, [
            $this,
            'buildObject'
        ], $this->getJoin());
        if (count($stages) === 1) {
            return $stages[0];
        }

        return null;
    }

    /**
     *
     * @return TournamentSeasonStage[]
     * @see \Sportal\FootballApi\Repository\Repository::findAll()
     */
    public function findAll($filter = array(), $langCode = null)
    {
        return $this->getAll($this->getModelClass(), $filter, [
            $this,
            'buildObject'
        ], $this->getJoin(), [
            [
                'key' => 'start_date'
            ]
        ]);
    }

    public function getJoin()
    {
        return [
            [
                'className' => $this->countryRepository->getModelClass(),
                'type' => 'left',
                'columns' => $this->countryRepository->getColumns()
            ],
            [
                'className' => $this->seasonRepository->getModelClass(),
                'type' => 'inner',
                'columns' => $this->seasonRepository->getColumns()
            ]
        ];
    }

    /**
     *
     * @param array $stageArr
     * @param boolean $partial
     * @return \Sportal\FootballApi\Model\TournamentSeasonStage|\Sportal\FootballApi\Model\PartialStage
     */
    public function buildObject(array $stageArr, $partial = false)
    {
        if (isset($stageArr['country'])) {
            $stageArr['country'] = $this->countryRepository->createObject($stageArr['country']);
        }

        return ($partial) ? $this->createPartial($stageArr) : $this->createObject($stageArr);
    }

    /**
     *
     * @param \Sportal\FootballApi\Model\Team[] $teams
     */
    public function setTeams(TournamentSeasonStage $stage, array $teams)
    {
        $this->linkToMany($stage, $teams, true);
    }

    /**
     *
     * @param integer $seasonId
     * @return \Sportal\FootballApi\Model\Team[]
     */
    public function getTeamsBySeason($seasonId)
    {
        return $this->getTeams(
            [
                [
                    'table' => $this->getPersistanceName($this->getModelClass()),
                    'key' => 'tournament_season_id',
                    'sign' => '=',
                    'value' => $seasonId
                ]
            ]);
    }

    /**
     *
     * @param integer $stageId
     * @return \Sportal\FootballApi\Model\Team[]
     */
    public function getTeamsByStage($stageId)
    {
        return $this->getTeams([
            'tournament_season_stage_id' => $stageId
        ]);
    }

    public function getModelClass()
    {
        return TournamentSeasonStage::class;
    }

    protected function getTeams(array $filter)
    {
        $callback = function (array $row) {
            return $this->teamRepository->createPartial($row['team']);
        };

        return $this->queryTable('tournament_season_stage_team', $filter, $callback,
            [
                [
                    'className' => $this->teamRepository->getModelClass(),
                    'type' => 'inner',
                    'columns' => $this->teamRepository->getPartialColumns()
                ],
                [
                    'className' => $this->getModelClass(),
                    'type' => 'inner',
                    'columns' => []
                ]
            ]);
    }

    /**
     *
     * @param PartialStage $stage
     * @param array $stageArr
     */
    private function setStageInfo(PartialStage $stage, array $stageArr)
    {
        $stage->setId($stageArr['id'])
            ->setName($stageArr['name'])
            ->setCup($stageArr['cup'])
            ->setTournamentSeasonId($stageArr['tournament_season_id'])
            ->setTournamentId($stageArr['tournament_season']['tournament_id'])
            ->setCountry($stageArr['country'])
            ->setType($stageArr['type'] ?? null);
        if (isset($stageArr['confederation'])) {
            $stage->setConfederation($stageArr['confederation']);
        }
    }

    public function findStageIds(StageFilter $filter): array
    {
        $qb = $this->conn->createQueryBuilder();
        $qb->select('tss.id')->from('tournament_season_stage', 'tss')
            ->innerJoin('tss', 'tournament_season', 'ts', 'tss.tournament_season_id = ts.id');
        $expr = null;
        if (!empty($filter->getTournamentOrder())) {
            $qb->innerJoin('ts', 'tournament_order', 't_order', 't_order.tournament_id = ts.tournament_id');
            $expr = $qb->expr()->eq('t_order.client_code', $qb->createPositionalParameter($filter->getTournamentOrder()));
        }
        if (!empty($filter->getTournamentIds())) {
            $expr = $qb->expr()->in('ts.tournament_id', $filter->getTournamentIds());
        }
        if (!empty($filter->getSeasonIds())) {
            $expr = $qb->expr()->in('ts.id', $filter->getSeasonIds());
        }
        if ($expr !== null) {
            $qb->where($expr);
            $stmt = $qb->execute();
            $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return array_column($data, 'id');
        } else {
            return [];
        }
    }
}