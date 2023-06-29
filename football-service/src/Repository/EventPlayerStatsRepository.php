<?php
namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Repository\Repository;
use Sportal\FootballApi\Model\EventPlayerStats;

/**
 * @author kstoilov
 *
 */
class EventPlayerStatsRepository extends Repository
{

    /**
     *
     * @param array $data
     * @return \Sportal\FootballApi\Model\EventPlayerStats
     */
    public function createObject(array $data)
    {
        return (new EventPlayerStats())->setEventPlayerId($data['event_player_id'])->setStatistics(
            is_array($data['statistics']) ? $data['statistics'] : json_decode($data['statistics'], true));
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::find()
     * @return \Sportal\FootballApi\Model\EventPlayerStats
     */
    public function find($id)
    {
        return $this->getByPk($this->getModelClass(), [
            'event_player_id' => $id
        ], [
            $this,
            'createObject'
        ]);
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::findAll()
     */
    public function findAll($filter = [])
    {
        return $this->getAll($this->getModelClass(), $filter, [
            $this,
            'createObject'
        ]);
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::getModelClass()
     */
    public function getModelClass()
    {
        return EventPlayerStats::class;
    }
}