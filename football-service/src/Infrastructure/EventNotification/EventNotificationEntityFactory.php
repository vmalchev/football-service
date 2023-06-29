<?php


namespace Sportal\FootballApi\Infrastructure\EventNotification;


use Sportal\FootballApi\Domain\EventNotification\EventNotificationEntityType;
use Sportal\FootballApi\Domain\EventNotification\EventNotificationOperationType;
use Sportal\FootballApi\Domain\EventNotification\IEventNotificationEntity;
use Sportal\FootballApi\Domain\EventNotification\IEventNotificationEntityFactory;

class EventNotificationEntityFactory implements IEventNotificationEntityFactory
{
    private string $entityId;

    private EventNotificationOperationType $operationType;

    private EventNotificationEntityType $entityType;

    /**
     * @param string $entityId
     * @return IEventNotificationEntityFactory
     */
    public function setEntityId(string $entityId): IEventNotificationEntityFactory
    {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * @param EventNotificationOperationType $operationType
     * @return IEventNotificationEntityFactory
     */
    public function setOperationType(EventNotificationOperationType $operationType): IEventNotificationEntityFactory
    {
        $this->operationType = $operationType;

        return $this;
    }

    /**
     * @param EventNotificationEntityType $entityType
     * @return IEventNotificationEntityFactory
     */
    public function setEntityType(EventNotificationEntityType $entityType): IEventNotificationEntityFactory
    {
        $this->entityType = $entityType;

        return $this;
    }

    public function setEntity(IEventNotificationEntity $eventNotificationEntity): IEventNotificationEntityFactory
    {
        return (clone $this)
            ->setEntityId($eventNotificationEntity->getEntityId())
            ->setEntityType($eventNotificationEntity->getEntityType())
            ->setOperationType($eventNotificationEntity->getOperationType());
    }

    public function getEventNotificationEntityFactory(): IEventNotificationEntityFactory
    {
        return clone $this;
    }

    public function create(): IEventNotificationEntity
    {
        return new EventNotificationEntity($this->entityId, $this->operationType, $this->entityType);
    }
}