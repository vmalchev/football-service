<?php

namespace Sportal\FootballApi\Repository;

use Doctrine\DBAL\Connection;
use Sportal\FootballApi\Cache\CacheManager;
use Sportal\FootballApi\Model\TeamPerson;

abstract class TeamPersonRepository extends Repository
{

    /**
     *
     * @var TeamRepository
     */
    protected $teamRepository;

    protected $personRepository;

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::__construct()
     */
    public function __construct(Connection $conn, CacheManager $cacheManager, TeamRepository $teamRepository,
                                PersonRepository $personRepository)
    {
        parent::__construct($conn, $cacheManager);
        $this->personRepository = $personRepository;
        $this->teamRepository = $teamRepository;
    }

    public function createObject(array $data)
    {
        $className = $this->getModelClass();
        $personProp = $this->getPersistanceName($this->personRepository->getModelClass());
        $object = new $className();
        $object->setId($data['id']);
        $object->setTeam($data['team']);
        $object->setPerson($data[$personProp]);
        $object->setActive($data['active']);

        if (isset($data['start_date'])) {
            $object->setStartDate(new \DateTime($data['start_date']));
        }

        if (isset($data['end_date'])) {
            $object->setEndDate(new \DateTime($data['end_date']));
        }

        return $object;
    }

    public function buildObject(array $data)
    {
        $personProp = $this->getPersistanceName($this->personRepository->getModelClass());
        $data[$personProp] = $this->personRepository->buildObject($data[$personProp]);
        $data['team'] = $this->teamRepository->createPartial($data['team']);
        return $this->createObject($data);
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
     *
     * @param integer $teamId
     * @return \Sportal\FootballApi\Model\TeamPerson
     */
    public function findByTeam($teamId)
    {
        return $this->queryPersistance([
            'team_id' => $teamId
        ], [
            $this,
            'buildObject'
        ], $this->getJoin());
    }

    /**
     *
     * @param integer $teamId
     * @return \Sportal\FootballApi\Model\TeamPerson
     */
    public function getCurrent($teamId)
    {
        return $this->findAll([
            'team_id' => $teamId,
            'active' => 1
        ]);
    }

    /**
     * {@inheritDoc}
     * @return \Sportal\FootballApi\Model\TeamPerson
     * @see \Sportal\FootballApi\Repository\Repository::findAll()
     */
    public function findAll($filter = [], $order = [])
    {
        return $this->queryPersistance($filter, [
            $this,
            'buildObject'
        ], $this->getJoin(), $order);
    }

    public function getJoin()
    {
        static $columns = null;
        if ($columns === null) {
            $columns = [
                [
                    'className' => $this->personRepository->getModelClass(),
                    'type' => 'inner',
                    'columns' => $this->personRepository->getColumns(),
                    'join' => $this->personRepository->getJoin()
                ],
                [
                    'className' => $this->teamRepository->getModelClass(),
                    'type' => 'inner',
                    'columns' => $this->teamRepository->getColumns()
                ]
            ];
        }
        return $columns;
    }

    /**
     * @param string $teamId
     * @param TeamPerson[] $models
     * @throws \Throwable
     */
    public function upsert(string $teamId, array $models): void
    {
        $this->conn->transactional(function () use ($teamId, $models) {
            $this->conn->delete($this->getPersistanceName($this->getModelClass()), ['team_id' => $teamId]);
            foreach ($models as $model) {
                $this->conn->insert($this->getPersistanceName($this->getModelClass()), $this->convertValues($model->getPersistanceMap()));
            }
        });
    }
}