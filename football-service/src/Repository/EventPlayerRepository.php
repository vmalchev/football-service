<?php
namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Database\Database;
use Sportal\FootballApi\Database\ModelInterface;
use Sportal\FootballApi\Database\Query\Query;
use Sportal\FootballApi\Database\Query\SortDirection;
use Sportal\FootballApi\Model\EventPlayer;
use Sportal\FootballApi\Model\EventPlayerSubOn;
use Sportal\FootballApi\Model\EventPlayerType;

class EventPlayerRepository
{
    private $db;

    private $playerRepository;

    private $typeRepository;

    public function __construct(Database $db, PlayerRepository $playerRepository,
        EventPlayerTypeRepository $typeRepository)
    {
        $this->db = $db;
        $this->playerRepository = $playerRepository;
        $this->typeRepository = $typeRepository;
    }

    public function buildObject(array $eventPlayerArr)
    {
        if (isset($eventPlayerArr['player'])) {
            $eventPlayerArr['player'] = $this->playerRepository->createPartialObject($eventPlayerArr['player']);
        }

        $eventPlayerArr['event_player_type'] = $this->typeRepository->createObject($eventPlayerArr['event_player_type']);

        return $this->createObject($eventPlayerArr);
    }

    public function createObject(array $data)
    {
        $eventPlayer = (new EventPlayer())->setId($data['id'])
            ->setEventId($data['event_id'])
            ->setEventPlayerType($data['event_player_type'])
            ->setHomeTeam($data['home_team'])
            ->setPlayerName($data['player_name']);

        if (isset($data['player'])) {
            $eventPlayer->setPlayer($data['player']);
        }

        if (isset($data['position_x'])) {
            $eventPlayer->setPositionX($data['position_x']);
        }

        if (isset($data['position_y'])) {
            $eventPlayer->setPositionY($data['position_y']);
        }

        if (! empty($data['shirt_number'])) {
            $eventPlayer->setShirtNumber($data['shirt_number']);
        }

        if (isset($data['sub_in'])) {
            $eventPlayer->setSubIn($data['sub_in']);
        }

        if (isset($data['sub_on'])) {
            $subOnArray = is_array($data['sub_on']) ? $data['sub_on'] : json_decode($data['sub_on'], true);
            $eventPlayer->setSubOn(new EventPlayerSubOn($subOnArray));
        }

        return $eventPlayer;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::find()
     * @return EventPlayer
     */
    public function find($id)
    {
        $query = $this->db->createQuery()->whereEquals('id', $id);
        $result = $this->queryDatabase($query);

        return (! empty($result)) ? $result[0] : null;
    }

    /**
     * Get EventPlayers for a specific event id
     * @param integer $eventId
     * @param string $langCode
     * @return \Sportal\FootballApi\Model\EventPlayer[]
     */
    public function findByEvent($eventId)
    {
        $query = $this->db->createQuery();
        $query->whereEquals(EventPlayer::EVENT_INDEX, $eventId)
            ->addOrderBy('home_team', SortDirection::DESC)
            ->addOrderBy('sortorder', SortDirection::ASC, EventPlayerType::class)
            ->addOrderBy('position_x')
            ->addOrderBy('position_y');

        return $this->queryDatabase($query);
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::findAll()
     * @return EventPlayer[]
     */
    public function findAll($filter = [])
    {
        return $this->getAll($this->getModelClass(), $filter, [
            $this,
            'buildObject'
        ], $this->getJoin(),
             [
                 [
                     'key' => 'home_team',
                     'direction' => 'DESC'
                 ],
                 [
                     'object' => $this->typeRepository->getPersistanceName($this->typeRepository->getModelClass()),
                     'key' => 'sortorder'
                 ],
                 [
                     'key' => 'position_x'
                 ],
                 [
                     'key' => 'position_y'
                 ]
             ]
        );
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::getModelClass()
     */
    public function getModelClass()
    {
        return EventPlayer::class;
    }

    public function updatePlayer($player, $eventPlayerId)
    {
        $eventPlayer = $this->find($eventPlayerId);
        if ($eventPlayer !== null) {
            $eventPlayer->setPlayer($player);
            $this->db->update($eventPlayer);
            $this->db->flush();
        }
    }

    public function patchExisting(EventPlayer $existing, EventPlayer $updated)
    {
        $updated->setId($existing->getId());
        return $updated;
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

    protected function getJoinList()
    {
        $factory = $this->db->getJoinFactory();
        return [
            $factory->createInner($this->typeRepository->getModelClass(), $this->typeRepository->getColumns()),
            $factory->createLeft($this->playerRepository->getModelClass(), $this->playerRepository->getPartialColumns())
        ];
    }

    public function create($lineup)
    {
        $this->db->insert($lineup);
    }

    public function update($existing, $updated)
    {
        $this->db->update($updated);
    }

    public function delete($model)
    {
        $this->db->delete($model);
    }

    public function flush()
    {
        $this->db->flush();
    }

    public function getChanges(ModelInterface $existing, ModelInterface $updated)
    {
        $changed = [];
        $updatedMap = $updated->getPersistanceMap();
        foreach ($existing->getPersistanceMap() as $key => $value) {
            if ($updatedMap[$key] != $value) {
                $changed[] = $key;
            }
        }

        return $changed;
    }
}