<?php

namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Cache\Index\GeneratesIndexName;
use Sportal\FootballApi\Database\Database;
use Sportal\FootballApi\Database\Query\Query;
use Sportal\FootballApi\Database\Query\SortDirection;
use Sportal\FootballApi\Model\EventIncident;
use Sportal\FootballApi\Model\ModelInterface;

class EventIncidentRepository extends Repository
{
    use GeneratesIndexName;

    private $playerRepository;

    private $eventRepository;

    private $db;

    public function __construct(Database $db, PlayerRepository $playerRepository,
                                EventRepository $eventRepository)
    {
        $this->db = $db;
        $this->playerRepository = $playerRepository;
        $this->eventRepository = $eventRepository;
    }

    public function createObject(array $eventIncidentArr)
    {
        $eventIncident = (new EventIncident())->setId($eventIncidentArr['id'])
            ->setEventId($eventIncidentArr['event_id'])
            ->setType($eventIncidentArr['type'])
            ->setHomeTeam($eventIncidentArr['home_team'])
            ->setMinute($eventIncidentArr['minute']);
        if (isset($eventIncidentArr['player_name'])) {
            $eventIncident->setPlayerName($eventIncidentArr['player_name']);
        }

        if (isset($eventIncidentArr['goal_home']) && isset($eventIncidentArr['goal_away'])) {
            $eventIncident->setGoalHome($eventIncidentArr['goal_home'])->setGoalAway($eventIncidentArr['goal_away']);
        }

        if (isset($eventIncidentArr['player'])) {
            $eventIncident->setPlayer($eventIncidentArr['player']);
        }

        if (isset($eventIncidentArr['rel_player_name'])) {
            $eventIncident->setRelPlayerName($eventIncidentArr['rel_player_name']);
        }

        if (isset($eventIncidentArr['rel_player'])) {
            $eventIncident->setRelPlayer($eventIncidentArr['rel_player']);
        }
        if (isset($eventIncidentArr['sortorder'])) {
            $eventIncident->setSortorder($eventIncidentArr['sortorder']);
        }

        if (isset($eventIncidentArr['deleted'])) {
            $eventIncident->setDeleted($eventIncidentArr['deleted']);
        }

        if (isset($eventIncidentArr['updated_at'])) {
            $eventIncident->setUpdatedAt(new \DateTime($eventIncidentArr['updated_at']));
        }

        if (isset($eventIncidentArr['team_id'])) {
            $eventIncident->setTeamId($eventIncidentArr['team_id']);
        }

        return $eventIncident;
    }

    public function buildObject(array $eventIncidentArr)
    {
        if (isset($eventIncidentArr['player'])) {
            $eventIncidentArr['player'] = $this->playerRepository->createPartialObject($eventIncidentArr['player']);
        }
        if (isset($eventIncidentArr['rel_player'])) {
            $eventIncidentArr['rel_player'] = $this->playerRepository->createPartialObject(
                $eventIncidentArr['rel_player']);
        }

        $teamId = !empty($eventIncidentArr['home_team']) ? $eventIncidentArr['event']['home_id'] : $eventIncidentArr['event']['away_id'];
        $eventIncidentArr['team_id'] = $teamId;

        return $this->createObject($eventIncidentArr);
    }

    /**
     *
     * {@inheritDoc}
     * @return \Sportal\FootballApi\Model\EventIncident
     * @see \Sportal\FootballApi\Repository\Repository::find()
     */
    public function find($id)
    {
        $query = $this->db->createQuery()->whereEquals('id', $id);
        $data = $this->queryDatabase($query);
        return (!empty($data)) ? $data[0] : null;
    }

    public function delete(ModelInterface $model)
    {
        $updated = clone $model;
        $updated->setDeleted(true);
        $this->update($updated);
    }

    /**
     *
     * @param \DateTime $updatedAfter
     * @return \Sportal\FootballApi\Model\EventIncident[]
     */
    public function findUpdatedAfter(\DateTime $updatedAfter)
    {
        $indexStart = clone $updatedAfter;
        $query = $this->db->createQuery();
        $expr = $query->andX();
        $expr->gteq(EventIncident::INDEX_UPDATED, $this->db->formatTime($indexStart));
        $query->where($expr)->addOrderBy(EventIncident::INDEX_UPDATED, SortDirection::DESC);
        return $this->queryDatabase($query);
    }

    /**
     *
     * @param integer $eventId
     * @param string $sortDirection
     * @return EventIncident[]
     */
    public function findByEvent($eventId, $sortDirection = null)
    {
        $query = $this->db->createQuery()
            ->addOrderBy('minute', $sortDirection)
            ->addOrderBy('sortorder', $sortDirection);
        $expr = $query->andX();
        $expr->eq(EventIncident::INDEX_EVENT, $eventId);
        $expr->eq('deleted', 0);
        $query->where($expr);
        return $this->queryDatabase($query);
    }

    public function updatePlayerId($player, $eventIncidentId)
    {
        $eventIncident = $this->find($eventIncidentId);
        $eventIncident->setPlayer($this->playerRepository->clonePartial($player));
        $this->update($eventIncident);
    }

    public function updateRelPlayerId($player, $eventIncidentId)
    {
        $eventIncident = $this->find($eventIncidentId);
        $eventIncident->setRelPlayer($this->playerRepository->clonePartial($player));
        $this->update($eventIncident);
    }

    public function getModelClass()
    {
        return EventIncident::class;
    }

    public function create(ModelInterface $model)
    {
        $this->db->insert($model);
        $this->db->flush();
    }

    public function patchExisting(ModelInterface $existing, ModelInterface $updated)
    {
        $updated->setId($existing->getId());
        return $updated;
    }

    public function update(ModelInterface $model)
    {
        $this->db->update($model);
        $this->db->flush();
    }

    protected function getJoinList()
    {
        $factory = $this->db->getJoinFactory();
        return [
            $factory->createLeft($this->playerRepository->getModelClass(), $this->playerRepository->getPartialColumns()),
            $factory->createLeft($this->playerRepository->getModelClass(), $this->playerRepository->getPartialColumns(),
                'rel_player_id', 'rel_player'),
            $factory->createInner($this->eventRepository->getModelClass(),
                [
                    'home_id',
                    'away_id'
                ])
        ];
    }

    protected function queryDatabase(Query $query)
    {
        $query->from($this->getModelClass())
            ->addJoinList($this->getJoinList());
        return $this->db->executeQuery($query, [
            $this,
            'buildObject'
        ]);
    }

    public function findAll($filter = [])
    {
        return [];
    }
}