<?php
namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Repository\Repository;
use Sportal\FootballApi\Model\EventPlayerType;

/**
 * @author kstoilov
 *
 */
class EventPlayerTypeRepository extends Repository
{

    /**
     *
     * @param array $data
     * @return \Sportal\FootballApi\Model\EventPlayerType
     */
    public function createObject(array $data)
    {
        $type = (new EventPlayerType())->setId($data['id'])
            ->setCategory($data['category'])
            ->setName($data['name'])
            ->setCode($data['code']);
        if (isset($data['sortorder'])) {
            $type->setSortorder($data['sortorder']);
        }
        return $type;
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
        return EventPlayerType::class;
    }
}