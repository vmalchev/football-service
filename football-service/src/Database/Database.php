<?php

namespace Sportal\FootballApi\Database;

use Doctrine\DBAL\Connection;
use Sportal\FootballApi\Database\Query\JoinFactory;
use Sportal\FootballApi\Database\Query\Query;
use Sportal\FootballApi\Util\ModelNameUtil;

class Database
{

    /**
     *
     * @var Connection
     */
    private $conn;

    /**
     *
     * @var ModelNameUtil
     */
    private $nameUtil;

    private $joinFactory;

    private $updates;

    private $inserts;

    private $deletes;

    public function __construct(Connection $conn, ModelNameUtil $nameUtil)
    {
        $this->conn = $conn;
        $this->nameUtil = $nameUtil;
        $this->joinFactory = new JoinFactory();
    }

    /**
     *
     * @return \Sportal\FootballApi\Database\Query\Query
     */
    public function createQuery()
    {
        return new Query($this->nameUtil);
    }

    /**
     *
     * @return \Sportal\FootballApi\Database\Query\JoinFactory
     */
    public function getJoinFactory()
    {
        return $this->joinFactory;
    }

    public function getSingleResult(Query $query, callable $callback)
    {
        $data = $this->executeQuery($query, $callback);
        if (!empty($data)) {
            return $data[0];
        }
        return null;
    }

    public function executeQuery(Query $query, callable $callback)
    {
        $qb = $this->conn->createQueryBuilder();
        $query->build($qb);
        $stmt = $qb->execute();
        $data = [];
        $hasJoin = $query->hasJoin();
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            if ($hasJoin) {
                $row = $query->expandRow($row);
            }
            $data[] = $callback($row);
        }
        return $data;
    }

    public function insert(ModelInterface $model)
    {
        $this->inserts[] = $model;
        $this->updateTimestamp($model);
    }

    public function update(ModelInterface $model)
    {
        $this->updates[] = $model;
        $this->updateTimestamp($model);
    }

    public function delete(ModelInterface $model)
    {
        $this->deletes[] = $model;
    }

    public function flush()
    {
        if (!empty($this->inserts) || !empty($this->updates) || !empty($this->deletes)) {
            try {
                $this->conn->transactional(
                    function ($conn) {
                        $this->runInserts($conn);
                        $this->runUpdates($conn);
                        $this->runDeletes($conn);
                    });
            } finally {
                $this->cancel();
            }
        }
    }

    public function formatTime(\DateTime $date)
    {
        $date->setTimezone(new \DateTimeZone('UTC'));
        return $date->format("Y-m-d H:i:s");
    }

    public function cancel()
    {
        $this->updates = [];
        $this->inserts = [];
        $this->deletes = [];
    }

    private function runUpdates(Connection $conn)
    {
        if (!empty($this->updates)) {
            foreach ($this->updates as $model) {
                $tableName = $this->nameUtil->persistanceName($model);
                $values = $model->getPersistanceMap();
                $primaryKeys = $model->getPrimaryKeyMap();
                if (method_exists($model, 'getUpdatedAt')) {
                    $values['updated_at'] = $this->formatTime($model->getUpdatedAt());
                }
                $conn->update($tableName, $this->convertValues($values), $primaryKeys);
            }
        }
    }

    private function runInserts(Connection $conn)
    {
        if (!empty($this->inserts)) {
            foreach ($this->inserts as $model) {
                $tableName = $this->nameUtil->persistanceName($model);
                $values = $model->getPersistanceMap();
                if (method_exists($model, 'getUpdatedAt')) {
                    $values['updated_at'] = $this->formatTime($model->getUpdatedAt());
                }
                $affected = $conn->insert($tableName, $this->convertValues($values));
                if ($affected === 1 && $model instanceof SurrogateKeyInterface) {
                    $id = (int)$conn->lastInsertId($tableName . "_id_seq");
                    $model->setId($id);
                }
            }
        }
    }

    private function runDeletes(Connection $conn)
    {
        if (!empty($this->deletes)) {
            foreach ($this->deletes as $model) {
                $tableName = $this->nameUtil->persistanceName($model);
                $primaryKeys = $model->getPrimaryKeyMap();
                $this->conn->delete($tableName, $primaryKeys);
            }
        }
    }

    private function updateTimestamp(ModelInterface $model)
    {
        if (method_exists($model, 'setUpdatedAt')) {
            $dateTime = new \DateTime();
            $dateTime->setTimezone(new \DateTimeZone('UTC'));
            $model->setUpdatedAt($dateTime);
        }
    }

    private function convertValues($values)
    {
        foreach ($values as $key => $value) {
            if ($value === true) {
                $values[$key] = 1;
            } elseif ($value === false) {
                $values[$key] = 0;
            }
        }
        return $values;
    }
}


