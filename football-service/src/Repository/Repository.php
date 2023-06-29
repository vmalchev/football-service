<?php
namespace Sportal\FootballApi\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Sportal\FootballApi\Cache\CacheManager;
use Sportal\FootballApi\Model\JsonColumnContainerInterface;
use Sportal\FootballApi\Model\ModelInterface;
use Sportal\FootballApi\Model\SurrogateKeyInterface;
use Sportal\FootballApi\Util\NameUtil;
use Sportal\FootballApi\Asset\Assetable;
use Sportal\FootballApi\Cache\IndexableInterface;

abstract class Repository
{

    const TIMESTAMP_FORMAT = "Y-m-d H:i:s";

    const EXPR_TYPE_OR = 'OR';

    /**
     *
     * @var Connection
     */
    protected $conn;

    protected $cacheManager;

    public function __construct(Connection $conn, CacheManager $cacheManager)
    {
        $this->conn = $conn;
        $this->cacheManager = $cacheManager;
    }

    protected function getByPk($model, array $keys, callable $callback, $join = array())
    {
        $tableName = $this->getPersistanceName($model);
        
        $populator = function () use ($tableName, $keys, $callback, $join) {
            $data = $this->queryTable($tableName, $keys, $callback, $join);
            return isset($data[0]) ? $data[0] : null;
        };
        
        $cached = $this->cacheManager->getInstance($tableName, $keys);
        if ($cached === null) {
            $cached = $this->cacheManager->populateInstance($tableName, $keys, $populator);
        }
        return $cached;
    }

    protected function queryTable($tableName, array $filter, callable $callback, $join = array(), $order = array(),
        array $limit = null)
    {
        $stmt = $this->query($tableName, $filter, $join, $order, [], $limit);
        $data = [];
        $hasJoin = ! empty($join);
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            if ($hasJoin) {
                list ($expandedJoin) = $this->expandRow($row, $join);
                $data[] = $callback($expandedJoin);
            } else {
                $data[] = $callback($row);
            }
        }
        return $data;
    }

    protected function queryPersistance(array $filter, callable $callback, $join = array(), $order = array(), array $limit = null)
    {
        $tableName = $this->getPersistanceName($this->getModelClass());
        return $this->queryTable($tableName, $filter, $callback, $join, $order, $limit);
    }

    protected function getAll($model, array $filter, callable $callback, $join = array(), $order = array())
    {
        $tableName = $this->getPersistanceName($model);
        
        $populator = function () use ($tableName, $filter, $callback, $join, $order) {
            return $this->queryTable($tableName, $filter, $callback, $join, $order);
        };
        
        $parameters = $this->getParamsArray($filter, $join, $order);
        $list = $this->cacheManager->getList($tableName, $parameters);
        if ($list === null) {
            $list = $this->cacheManager->populateList($tableName, $parameters, $populator);
        }
        
        return $list;
    }

    protected function getParamsArray(array $filter = [], array $join = [], array $order = [])
    {
        $parameters = [];
        if (! empty($filter)) {
            $parameters['filter'] = $filter;
        }
        if (! empty($order)) {
            $parameters['order'] = $order;
        }
        if (! empty($join)) {
            $parameters['join'] = $join;
        }
        return $parameters;
    }

    protected function expandRow(array $row, array $join, $tableId = 1)
    {
        $expandedJoin = [];
        $start = ($tableId == 1);
        foreach ($join as $rel) {
            $alias = "t" . $tableId . "_";
            $objName = isset($rel['object']) ? $rel['object'] : $this->getPersistanceName($rel['className']);
            $joinData = [];
            foreach ($rel['columns'] as $column) {
                $key = $alias . $column;
                if ($row[$key] !== null) {
                    $joinData[$column] = $row[$key];
                }
                unset($row[$key]);
            }
            if (isset($rel['join'])) {
                list ($row, $tableId, $joinedColumns) = $this->expandRow($row, $rel['join'], $tableId + 1);
                $joinData = array_merge($joinData, $joinedColumns);
            } else {
                $tableId ++;
            }
            
            if (! empty($joinData)) {
                $expandedJoin[$objName] = $joinData;
            }
        }
        
        if ($start) {
            $row = array_merge($row, $expandedJoin);
        }
        
        return [
            $row,
            $tableId,
            $expandedJoin
        ];
    }

    private function join(QueryBuilder $qb, array $join, $fromAlias, $tableId = 1, $forceLeftJoin = false)
    {
        $columns = [];
        $aliases = [];
        foreach ($join as $rel) {
            $className = $rel['className'];
            if (is_subclass_of($className, SurrogateKeyInterface::class)) {
                $alias = 't' . $tableId;
                $tableName = $this->getPersistanceName($className);
                $aliases[isset($rel['object']) ? $rel['object'] : $tableName] = $alias;
                $joinKey = isset($rel['key']) ? $rel['key'] : $tableName . "_id";
                $leftJoin = $rel['type'] == 'left' || $forceLeftJoin;
                if ($leftJoin) {
                    $qb->leftJoin($fromAlias, $tableName, $alias, "$alias.id = $fromAlias." . $joinKey);
                } elseif ($rel['type'] == 'inner') {
                    $qb->innerJoin($fromAlias, $tableName, $alias, "$alias.id = $fromAlias." . $joinKey);
                }
                foreach ($rel['columns'] as $column) {
                    $columns[] = $alias . "." . $column . " as " . $alias . "_" . $column;
                }
                if (isset($rel['join']) && is_array($rel['join'])) {
                    list ($addedColumns, $tableId, $addedAlias) = $this->join($qb, $rel['join'], $alias, $tableId + 1,
                        $leftJoin);
                    foreach ($addedColumns as $column) {
                        $columns[] = $column;
                    }
                    foreach ($addedAlias as $key => $value) {
                        $aliases[$key] = $value;
                    }
                } else {
                    $tableId ++;
                }
            }
        }
        return [
            $columns,
            $tableId,
            $aliases
        ];
    }

    private function query($tableName, array $filter, $join = array(), $order = array(), $select = array(), array $limit = null)
    {
        $qb = $this->conn->createQueryBuilder();
        $tAlias = 't0';
        $columns = [];
        if (count($select) > 0) {
            foreach ($select as $column) {
                $columns[] = $tAlias . '.' . $column;
            }
        } else {
            $columns[0] = $tAlias . '.*';
        }
        
        $qb->select($columns)->from($tableName, $tAlias);
        
        if (count($join) > 0) {
            list ($joinColumns, $tableId, $aliases) = $this->join($qb, $join, $tAlias);
            if (count($select) == 0) {
                foreach ($joinColumns as $column) {
                    $columns[] = $column;
                }
                $qb->select($columns);
            }
        }
        
        if (count($filter) > 0) {
            $andX = $qb->expr()->andX();
            $childExpr = null;
            foreach ($filter as $key => $value) {
                if (is_array($value)) {
                    $field = (isset($value['table']) ? $aliases[$value['table']] : $tAlias) . "." . $value['key'];
                    if ($value['sign'] == 'in') {
                        $in = [];
                        foreach ($value['value'] as $value) {
                            $in[] = $qb->createNamedParameter($value);
                        }
                        $part = $qb->expr()->in($field, $in);
                    } else {
                        $part = $field . " " . $value['sign'] . " " . $qb->createNamedParameter($value['value']);
                    }
                    
                    if (isset($value['expr_type']) && $value['expr_type'] == static::EXPR_TYPE_OR) {
                        if ($childExpr === null) {
                            $childExpr = $qb->expr()->orX();
                            $andX->add($childExpr);
                        }
                        $childExpr->add($part);
                    } elseif ($childExpr !== null) {
                        $childExpr->add($part);
                        $childExpr = null;
                    } else {
                        $andX->add($part);
                    }
                } else {
                    $andX->add("t0.$key = " . $qb->createNamedParameter($value));
                }
            }
            $qb->where($andX);
        }
        
        if (isset($order['keys']) && count($order['keys']) > 0) {
            $sort = implode(',',
                array_map(function ($value) {
                    return "t0." . $value;
                }, $order['keys']));
            $qb->orderBy($sort, $order['direction']);
        } elseif (! empty($order)) {
            foreach ($order as $orderArr) {
                $sort = isset($orderArr['object']) ? $aliases[$orderArr['object']] . "." . $orderArr['key'] : $tAlias .
                     "." . $orderArr['key'];
                $qb->addOrderBy($sort, isset($orderArr['direction']) ? $orderArr['direction'] : null);
            }
        }
        
        if (isset($limit) && isset($limit['first_result'])) {
            $qb->setFirstResult($limit['first_result']);
        }
        
        if (isset($limit) && isset($limit['max_results'])) {
            $qb->setMaxResults($limit['max_results']);
        }
        
        return $qb->execute();
    }

    protected function convertValues($values)
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

    /**
     *
     * @param ModelInterface $model
     * @param ModelInterface[] $children
     * @param boolean $replace whether to replace any existing links
     */
    public function linkToMany(ModelInterface $model, array $children, $replace = false)
    {
        $tableName = $this->getPersistanceName($model);
        $parentKeyMap = [];
        foreach ($model->getPrimaryKeyMap() as $key => $value) {
            $parentKeyMap[$tableName . "_" . $key] = $value;
        }
        if (! empty($parentKeyMap)) {
            $inserts = [];
            $relTable = null;
            foreach ($children as $child) {
                $childMap = [];
                $childTable = $this->getPersistanceName($child);
                foreach ($child->getPrimaryKeyMap() as $key => $value) {
                    $childMap[$childTable . '_' . $key] = $value;
                }
                $inserts[] = array_merge($parentKeyMap, $childMap);
                if ($relTable === null) {
                    $relTable = $tableName . "_" . $childTable;
                }
            }
            $this->conn->transactional(
                function () use ($relTable, $parentKeyMap, $inserts, $replace) {
                    if ($replace) {
                        $this->conn->delete($relTable, $parentKeyMap);
                    }
                    foreach ($inserts as $insert) {
                        $this->conn->insert($relTable, $insert);
                    }
                });
        }
    }

    public function getPersistanceName($model)
    {
        if (is_object($model)) {
            $model = get_class($model);
        }
        
        return NameUtil::persistanceName($model);
    }

    public function getChangedKeys(ModelInterface $existing, ModelInterface $updated)
    {
        $changed = [];
        $updatedMap = $updated->getPersistanceMap();
        foreach ($existing->getPersistanceMap() as $key => $value) {
            if ($updatedMap[$key] != $value) {
                $changed[] = $key;
            }
        }
        
        if ($existing instanceof JsonColumnContainerInterface && $updated instanceof JsonColumnContainerInterface) {
            $changedJson = $existing->getChangedJsonColumns($updated);
            $withoutJson = array_diff($changed, array_keys($existing->getJsonData()));
            $changed = array_merge($changedJson, $withoutJson);
        }
        
        return array_diff($changed, $this->getIgnoredKeys());
    }

    /**
     *
     * @param ModelInterface $existing
     * @param ModelInterface $updated
     * @return ModelInterface
     */
    public function patchExisting(ModelInterface $existing, ModelInterface $updated)
    {
        if ($updated instanceof SurrogateKeyInterface && $existing instanceof SurrogateKeyInterface) {
            $updated->setId($existing->getId());
        }
        return $updated;
    }

    public function hasChanged(ModelInterface $existing, ModelInterface $updated)
    {
        $changedKeys = $this->getChangedKeys($existing, $updated);
        return count($changedKeys) > 0;
    }

    public function update(ModelInterface $model)
    {
        $tableName = $this->getPersistanceName($model);
        $values = $model->getPersistanceMap();
        $primaryKeys = $model->getPrimaryKeyMap();
        if (method_exists($model, 'setUpdatedAt') && ! isset($values['updated_at'])) {
            $values['updated_at'] = gmdate("Y-m-d H:i:s");
            $model->setUpdatedAt(new \DateTime($values['updated_at']));
        }
        $affected = $this->conn->update($tableName, $this->convertValues($values), $primaryKeys);
        if ($affected === 1) {
            $this->cacheManager->setInstance($tableName, $primaryKeys, $model);
            if ($model instanceof IndexableInterface) {
                $this->cacheManager->getIndexManager()->update([
                    $model
                ]);
            }
        }
    }

    public function delete(ModelInterface $model)
    {
        $tableName = $this->getPersistanceName($model);
        $primaryKeys = $model->getPrimaryKeyMap();
        $affected = $this->conn->delete($tableName, $primaryKeys);
        if ($affected === 1) {
            $this->cacheManager->delInstance($tableName, $primaryKeys);
        }
    }

    public function refreshCache(array $changedKeys, array $createdModels)
    {
        $tableName = $this->getPersistanceName($this->getModelClass());
        $parameterList = $this->cacheManager->getParameters($tableName);
        
        foreach ($parameterList as $parameters) {
            list ($filter, $join, $order) = $this->parseParamArray($parameters);
            $refresh = function () use ($parameters) {
                return $this->getListPKs($parameters);
            };
            
            // New model created, have to always refresh the list
            if (count($createdModels) > 0) {
                // Order is irrelevant. We can just append the models to the cached list.
                if (empty($parameters['filter']) && empty($parameters['order'])) {
                    $this->appendCacheList($createdModels);
                } else {
                    $this->cacheManager->refreshList($tableName, $parameters, $refresh);
                }
            } else { // Just updated existing model, let's just update the filters that are relevant.
                $relevantParams = array_merge($filter, isset($order['keys']) ? $order['keys'] : []);
                foreach ($relevantParams as $field => $value) {
                    $searchKey = (is_array($value)) ? $value['key'] : $field;
                    if (in_array($searchKey, $changedKeys)) {
                        $this->cacheManager->refreshList($tableName, $parameters, $refresh);
                        break;
                    }
                }
            }
        }
    }

    protected static function isKeyRelevant(array $queryParams, $key, $matchValue = null)
    {
        $filter = isset($queryParams['filter']) ? $queryParams['filter'] : [];
        foreach ($filter as $field => $value) {
            $searchKey = $field;
            $searchValue = $value;
            if (is_array($value)) {
                $searchKey = $value['key'];
                $searchValue = $value['value'];
            }
            if ($searchKey == $key) {
                if ($matchValue === null || $searchValue == $matchValue) {
                    return true;
                }
            }
        }
        return false;
    }

    protected function parseParamArray(array $queryParams)
    {
        $filter = isset($queryParams['filter']) ? $queryParams['filter'] : [];
        $order = isset($queryParams['order']) ? $queryParams['order'] : [];
        $join = isset($queryParams['join']) ? $queryParams['join'] : [];
        return [
            $filter,
            $join,
            $order
        ];
    }

    public function getListPKs(array $queryParams)
    {
        $primaryKeys = $this->getPrimaryKeys();
        list ($filter, $join, $order) = $this->parseParamArray($queryParams);
        $stmt = $this->query($this->getPersistanceName($this->getModelClass()), $filter, $join, $order, $primaryKeys);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     *
     * @param ModelInterface[] $models
     * @return void
     */
    protected function appendCacheList(array $models)
    {
        if (count($models) > 0) {
            $persistanceName = $this->getPersistanceName($models[0]);
            $keyList = array_map(
                function (ModelInterface $model) {
                    return $model->getPrimaryKeyMap();
                }, $models);
            $this->cacheManager->addToList($persistanceName, [], $keyList);
        }
    }

    public function create(ModelInterface $model)
    {
        $tableName = $this->getPersistanceName($model);
        $values = $model->getPersistanceMap();
        if (method_exists($model, 'setUpdatedAt') && ! isset($values['updated_at'])) {
            $values['updated_at'] = gmdate("Y-m-d H:i:s");
            $model->setUpdatedAt(new \DateTime($values['updated_at']));
        }
        $affected = $this->conn->insert($tableName, $this->convertValues($values));
        if ($affected === 1) {
            if ($model instanceof SurrogateKeyInterface) {
                $id = (int) $this->conn->lastInsertId($tableName . "_id_seq");
                $model->setId($id);
            }
            $this->cacheManager->setInstance($tableName, $model->getPrimaryKeyMap(), $model);
            if ($model instanceof IndexableInterface) {
                $this->cacheManager->getIndexManager()->update([
                    $model
                ]);
            }
        }
    }

    protected function getPrimaryKeys()
    {
        static $keys = null;
        if ($keys === null) {
            $className = $this->getModelClass();
            $instance = new $className();
            if ($instance instanceof ModelInterface) {
                $keys = array_keys($instance->getPrimaryKeyMap());
            } else {
                throw new \DomainException(
                    "Class $className used by Repository: " . get_class($this) . " does not implement ModelInterface");
            }
        }
        return $keys;
    }

    protected function refreshList($persistanceName, $parameters)
    {
        $this->cacheManager->refreshList($persistanceName, $parameters,
            function () use ($parameters) {
                return $this->getListPKs($parameters);
            });
    }

    protected function refreshReleventLists(array $keyMap)
    {
        $persistanceName = $this->getPersistanceName($this->getModelClass());
        $params = $this->cacheManager->getParameters($persistanceName);
        foreach ($params as $parameters) {
            foreach ($keyMap as $key => $value) {
                if (static::isKeyRelevant($parameters, $key, $value)) {
                    $this->refreshList($persistanceName, $parameters);
                }
            }
        }
    }

    protected static function formatColumns(array $columns, $alias)
    {
        $format = [];
        foreach ($columns as $column) {
            $format[] = "$alias.$column as " . $alias . "_" . $column;
        }
        return $format;
    }

    /**
     * Return a list of columns which should be ignored when checking for model changes
     * @return string[]
     */
    protected function getIgnoredKeys()
    {
        $ignored = [
            'updated_at'
        ];
        $callable = [
            $this->getModelClass(),
            'getAssetColumns'
        ];
        if (is_callable($callable)) {
            foreach ($callable() as $value) {
                $ignored[] = $value;
            }
        }
        return $ignored;
    }

    public function getColumns()
    {
        static $columns = null;
        if ($columns === null) {
            $tableName = $this->getPersistanceName($this->getModelClass());
            $cached = $this->cacheManager->getColumns($tableName);
            if ($cached === null) {
                $cached = $this->cacheManager->populateColumns($tableName,
                    function () use ($tableName) {
                        $sql = "SELECT attname
                                FROM   pg_attribute
                                WHERE  attrelid = '$tableName'::regclass
                                AND    attnum > 0
                                AND    NOT attisdropped
                                ORDER  BY attnum";
                        $stmt = $this->conn->query($sql);
                        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
                    });
            }
            $columns = $cached;
        }
        return $columns;
    }

    public static function formatTime(\DateTime $date)
    {
        return $date->setTimezone(new \DateTimeZone('UTC'))->format(static::TIMESTAMP_FORMAT);
    }

    /**
     * Find a model in the repository its primary key.
     * @param mixed $id
     * @return ModelInterface
     */
    abstract public function find($id);

    /**
     *
     * @param array $filter key => value pairs through which to filter the models in the repository
     * @return ModelInterface[]
     */
    abstract public function findAll($filter = array());

    /**
     * @return string Fully qualified class of the model which the repository works with. Class has to implement ModelInterface.
     */
    abstract public function getModelClass();
}