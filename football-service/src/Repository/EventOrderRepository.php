<?php
namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Cache\Cache;
use Doctrine\DBAL\Connection;
use Sportal\FootballApi\Model\EventOrder;
use Sportal\FootballApi\Model\TournamentOrder;
use Sportal\FootballApi\Model\Event;

class EventOrderRepository
{

    private $cache;

    private $conn;

    private $tournamentOrder;

    private $manager;

    public function __construct(Connection $conn, Cache $cache, TournamentOrderRepository $tournamentOrder)
    {
        $this->conn = $conn;
        $this->cache = $cache;
        $this->tournamentOrder = $tournamentOrder;
        $this->manager = $this->cache->getIndexManager();
    }

    protected function getClientMap($clientCode)
    {
        $qb = $this->conn->createQueryBuilder();
        $qb->select(
            [
                'e.id as event_id',
                'torder.sortorder as sortorder',
                'torder.client_code as client_code'
            ])
            ->from('event', 'e')
            ->innerJoin('e', 'tournament_season_stage', 'tss', 'tss.id = e.tournament_season_stage_id')
            ->innerJoin('tss', 'tournament_season', 'ts', 'ts.id = tss.tournament_season_id')
            ->innerJoin('ts', 'tournament_order', 'torder', 'torder.tournament_id = ts.tournament_id')
            ->where('torder.client_code = ' . $qb->createPositionalParameter($clientCode));
        
        $stmt = $qb->execute();
        $results = [];
        
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $results[] = (new EventOrder())->setClientCode($row['client_code'])
                ->setEventId('event:' . $row['event_id'])
                ->setSortorder($row['sortorder']);
        }
        
        return $results;
    }

    protected function getClientCodes()
    {
        $stmt = $this->conn->executeQuery('select distinct(client_code) from tournament_order');
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     *
     * @param Event[] $events
     */
    public function addEvents(array $events)
    {
        $codes = $this->getClientCodes();
        foreach ($codes as $clientCode) {
            $map = $this->tournamentOrder->getClientMap($clientCode);
            foreach ($events as $event) {
                $tId = $event->getTournamentSeasonStage()->getTournamentId();
                if (isset($map[$tId])) {
                    $eventOrder = (new EventOrder())->setClientCode($clientCode)
                        ->setEventId($this->cache->generateKey($event))
                        ->setSortorder($map[$tId]);
                    $this->manager->add($eventOrder);
                }
            }
        }
        $this->manager->flush();
    }

    public function refresh($clientCode)
    {
        $map = $this->cache->getMap(EventOrder::class, $clientCode);
        $map->populate(function () use ($clientCode) {
            return $this->getClientMap($clientCode);
        });
    }

    public function orderEventIds(array $ids, $clientCode)
    {
        $populator = function () use ($clientCode) {
            return $this->getClientMap($clientCode);
        };
        
        $map = $this->cache->getMap(EventOrder::class, $clientCode)->getFields($ids, $populator);
        
        if (! empty($map)) {
            $lookup = [];
            $scale = count($ids);
            $num = 0;
            
            foreach ($ids as $key => $id) {
                if (! isset($map[$key])) {
                    unset($ids[$key]);
                } else {
                    $lookup[$id] = $map[$key] + $num / $scale;
                    $num ++;
                }
            }
            
            $compare = function ($idA, $idB) use ($lookup) {
                return $this->tournamentOrder->compare($lookup, $idA, $idB);
            };
            
            usort($ids, $compare);
        }
        
        return $ids;
    }
}