<?php

namespace Sportal\FootballApi\Infrastructure\Database;

use Doctrine\DBAL\Connection;
use Sportal\FootballApi\Domain\Database\ITransactionManager;
use Sportal\FootballApi\Infrastructure\Database\Query\CompositeExpression;
use Sportal\FootballApi\Infrastructure\Database\Query\JoinBuilder;
use Sportal\FootballApi\Infrastructure\Database\Query\JoinFactory;
use Sportal\FootballApi\Infrastructure\Database\Query\Query;
use Sportal\FootballApi\Infrastructure\Page\Page;
use Sportal\FootballApi\Infrastructure\Page\PageDataProvider;
use Sportal\FootballApi\Infrastructure\Page\PageMeta;
use Sportal\FootballApi\Infrastructure\Page\PageRequest;

class Database
{
    /**
     *
     * @var Connection
     */
    private Connection $conn;

    /**
     * @var ITransactionManager
     */
    private ITransactionManager $transactionManager;

    private PageDataProvider $pageRequestProvider;

    private JoinBuilder $joinBuilder;

    public function __construct(Connection $conn, ITransactionManager $transactionManager, PageDataProvider $pageRequestProvider,
                                JoinBuilder $joinBuilder)
    {
        $this->conn = $conn;
        $this->transactionManager = $transactionManager;
        $this->pageRequestProvider = $pageRequestProvider;
        $this->joinBuilder = $joinBuilder;
    }

    public function delete(string $tableName, array $identifier): int
    {
        return (new DatabaseUpdate($this->conn))->delete($tableName, $identifier);
    }

    public function orExpression(): CompositeExpression
    {
        return new CompositeExpression(CompositeExpression::TYPE_OR);
    }

    public function getJoinFactory(): JoinFactory
    {
        return new JoinFactory();
    }

    public function exists(string $tableName, array $identifier): bool
    {
        if (empty($identifier)) {
            throw new \InvalidArgumentException("Exists identifier is empty");
        }
        $expr = $this->andExpression();
        foreach ($identifier as $key => $value) {
            $expr->eq($key, $value);
        }
        $query = $this->createQuery($tableName, ['1'])->where($expr);
        return !empty($this->getQueryResults($query));
    }

    public function andExpression(): CompositeExpression
    {
        return new CompositeExpression(CompositeExpression::TYPE_AND);
    }

    public function createQuery(string $tableName, array $columns = null): Query
    {
        return new Query($tableName, $columns, $this->joinBuilder);
    }

    public function getPagedQueryResults(Query $query, callable $objectBuilder = null, ?PageRequest $pageRequestInput = null): Page
    {
        $pageRequest = !is_null($pageRequestInput) ? $pageRequestInput : $this->pageRequestProvider->getRequest();
        if ($pageRequest !== null) {
            $query->offset($pageRequest->getOffset())
                ->limit($pageRequest->getLimit());
        }

        $results = $this->getQueryResults($query, $objectBuilder);

        if ($pageRequest !== null) {
            $totalCount = $this->getTotalCount($query);
        } else {
            $totalCount = count($results);
        }

        $pageMeta = new PageMeta($totalCount, $query->getOffset(), $query->getLimit());
        $this->pageRequestProvider
            ->setMeta($pageMeta);

        return new Page($results, $pageMeta);
    }

    public function getQueryResults(Query $query, callable $objectBuilder = null): array
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
            if ($objectBuilder !== null) {
                $data[] = $objectBuilder($row);
            } else {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function getTotalCount(Query $query)
    {
        $qb = $this->conn->createQueryBuilder();
        $query->clearOrderBy()->offset(null)->limit(null)->build($qb);
        if ($query->hasDistinct()) {
            $stmt = $this->conn->executeQuery("select count(*) from ({$qb->getSQL()}) as resultset", $qb->getParameters(), $qb->getParameterTypes());
        } else {
            $qb->select("count(*)");
            $stmt = $qb->execute();
        }
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if (!empty($results)) {
            return (int)$results[0]['count'];
        }
        return 0;
    }

    public function getSingleResult(Query $query, callable $objectBuilder = null)
    {
        $data = $this->getQueryResults($query, $objectBuilder);
        if (!empty($data)) {
            return $data[0];
        }
        return null;
    }

    function transactional(\Closure $func)
    {
        return $this->transactionManager->transactional($func);
    }
}


