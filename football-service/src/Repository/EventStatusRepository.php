<?php
namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Model\EventStatus;
use Sportal\FootballApi\Database\Query\CompositeExpression;

class EventStatusRepository extends Repository
{

    /**
     *
     * @param array $eventStatusArr
     * @return \Sportal\FootballApi\Model\EventStatus
     */
    public function createObject(array $eventStatusArr)
    {
        $eventStatus = new EventStatus();
        $eventStatus->setName($eventStatusArr['name'])->setType($eventStatusArr['type']);
        if (isset($eventStatusArr['code'])) {
            $eventStatus->setCode($eventStatusArr['code']);
        }
        if (isset($eventStatusArr['id'])) {
            $eventStatus->setId($eventStatusArr['id']);
        }
        if (isset($eventStatusArr['short_name'])) {
            $eventStatus->setShortName($eventStatusArr['short_name']);
        }
        return $eventStatus;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::find()
     * @return \Sportal\FootballApi\Model\EventStatus
     */
    public function find($id)
    {
        return $this->getByPk(EventStatus::class, array(
            'id' => $id
        ), array(
            $this,
            'createObject'
        ));
    }

    /**
     *
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::findAll()
     * @return \Sportal\FootballApi\Model\EventStatus[]
     */
    public function findAll($filter = array())
    {
        return $this->getAll(EventStatus::class, $filter, [
            $this,
            'createObject'
        ]);
    }

    /**
     * Get array of event_status ids for type = 'inprogress'
     * @return integer[]
     */
    public function getInProgressIds()
    {
        return $this->getByType('inprogress');
    }

    /**
     * Get array of event_status ids for games that have not started
     * @return integer[]
     */
    public function getNotStartedIds()
    {
        return $this->getByType('notstarted');
    }

    public function getModelClass()
    {
        return EventStatus::class;
    }
    
    /**
     *
     * @return string[]
     */
    public function findStatusTypes(array $typeNotIn = []) {
        $statuses = $this->findAll();
        $types = [];
        foreach ($statuses as $status) {
            if (!in_array($status->getType(), $typeNotIn) && !in_array($status->getType(), $types)) {
                $types[] = $status->getType();
            }
        }
        return $types;
    }

    public function getLiveTypes()
    {
        return EventStatus::getLiveTypes();
    }

    public function getFinishedTypes()
    {
        return [
            EventStatus::TYPE_FINISHED
        ];
    }

    public function getNotStartedTypes()
    {
        return [
            EventStatus::TYPE_NOTSTARTED
        ];
    }

    public function createLiveExpr($joinId = null)
    {
        $expr = new CompositeExpression(CompositeExpression::TYPE_OR);
        $expr->eq('type', EventStatus::TYPE_INPROGRESS, $joinId);
        $expr->eq('code', EventStatus::CODE_INTERRUPTED, $joinId);
        return $expr;
    }

    public function getByType($type)
    {
        $statusTypes = $this->findAll();
        $ids = [];
        foreach ($statusTypes as $statusType) {
            if ($statusType->getType() == $type) {
                $ids[] = $statusType->getId();
            }
        }
        return $ids;
    }
}