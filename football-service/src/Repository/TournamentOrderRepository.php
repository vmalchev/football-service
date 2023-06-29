<?php
namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Cache\Cache;
use Sportal\FootballApi\Database\Database;
use Sportal\FootballApi\Database\Query\Query;
use Sportal\FootballApi\Model\TournamentOrder;
use Sportal\FootballApi\Model\Tournament;

class TournamentOrderRepository
{
    private $db;

    private $cache;

    private $indexManager;

    public function __construct(Database $db, Cache $cache)
    {
        $this->db = $db;
        $this->cache = $cache;
        $this->indexManager = $this->cache->getIndexManager();
    }

    public function createObject(array $data)
    {
        $tournamentOrder = new TournamentOrder();
        $tournamentOrder->setClientCode($data['client_code'])
            ->setTournamentId($data['tournament_id'])
            ->setSortorder($data['sortorder']);
        return $tournamentOrder;
    }

    public function find($clientCode, $tournamentId)
    {
        $query = $this->db->createQuery();
        $expr = $query->andX()
            ->eq(TournamentOrder::CLIENT_INDEX, $clientCode)
            ->eq('tournament_id', $tournamentId);
        $query->where($expr);
        $data = $this->queryDatabase($query);
        return (! empty($data)) ? $data[0] : null;
    }

    public function getClientMap($clientCode)
    {
        $populator = function () use ($clientCode) {
            $query = $this->db->createQuery()->whereEquals(TournamentOrder::CLIENT_INDEX, $clientCode);
            return $this->queryDatabase($query);
        };
        
        $map = $this->cache->getMap($this->getModelClass(), $clientCode)
            ->getAll($populator);
        
        return $map;
    }

    /**
     *
     * @param Tournament[] $tournaments
     * @param string $clientCode
     */
    public function addClientOrder(array $tournaments, $clientCode)
    {
        $map = $this->getClientMap($clientCode);
        foreach ($tournaments as $tournament) {
            if (isset($map[$tournament->getId()])) {
                $tournament->setClientSortOrder($map[$tournament->getId()]);
            }
        }
    }

    public function getTournamentMap($tournamentId)
    {
        $populator = function () use ($tournamentId) {
            $query = $this->db->createQuery()->whereEquals(TournamentOrder::TOURNAMENT_INDEX, $tournamentId);
            return $this->queryDatabase($query);
        };
        
        $map = $this->cache->getMap($this->getModelClass(), $tournamentId)
            ->getAll($populator);
        
        return $map;
    }

    public function orderTournaments(array $tournaments, $clientCode)
    {
        $map = $this->getClientMap($clientCode);
        
        if (! empty($map)) {
            foreach ($tournaments as $key => $tournament) {
                if (! isset($map[$tournament->getId()])) {
                    unset($tournaments[$key]);
                }
            }
            
            usort($tournaments,
                function ($a, $b) use ($map) {
                    return $this->compare($map, $a->getId(), $b->getId());
                });
            return $tournaments;
        }
        return [];
    }

    public function orderEvents(array $events, $clientCode)
    {
        $map = $this->getClientMap($clientCode);
        
        foreach ($events as $key => $event) {
            if (! isset($map[$event->getTournamentSeasonStage()->getTournamentId()])) {
                unset($events[$key]);
            }
        }
        
        usort($events,
            function ($a, $b) use ($map) {
                return $this->compare($map, $a->getTournamentSeasonStage()
                    ->getTournamentId(), $b->getTournamentSeasonStage()
                    ->getTournamentId());
            });
        
        return $events;
    }

    public function getModelClass()
    {
        return TournamentOrder::class;
    }

    public function compare(array $map, $tIda, $tIdb)
    {
        $a = isset($map[$tIda]) ? $map[$tIda] : PHP_INT_MAX;
        $b = isset($map[$tIdb]) ? $map[$tIdb] : PHP_INT_MAX;
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? - 1 : 1;
    }

    public function create(TournamentOrder $order)
    {
        $this->db->insert($order);
        $this->indexManager->add($order);
    }

    public function update(TournamentOrder $existing, TournamentOrder $updated)
    {
        $this->db->update($updated);
        $this->indexManager->update($existing, $updated);
    }

    public function flush()
    {
        $this->db->flush();
        $this->indexManager->flush();
    }

    protected function queryDatabase(Query $query)
    {
        $query->from($this->getModelClass());
        return $this->db->executeQuery($query, [
            $this,
            'createObject'
        ]);
    }

    public function getColumns()
    {
        return [
            TournamentOrder::TOURNAMENT_INDEX,
            TournamentOrder::CLIENT_INDEX,
        ];
    }
}