<?php


namespace Sportal\FootballApi\Infrastructure\EventNotification;


use Sportal\FootballApi\Domain\EventNotification\EventNotificationEntityType;
use Sportal\FootballApi\Domain\EventNotification\EventNotificationOperationType;
use Sportal\FootballApi\Domain\EventNotification\IEventNotificationEntity;

class EventNotificationEntity implements IEventNotificationEntity
{
    private string $entityId;

    private EventNotificationOperationType $operationType;

    private EventNotificationEntityType $entityType;

    /**
     * @param string $entityId
     * @param EventNotificationOperationType $operationType
     * @param EventNotificationEntityType $entityType
     */
    public function __construct(
        string $entityId,
        EventNotificationOperationType $operationType,
        EventNotificationEntityType $entityType
    ) {
        $this->entityId = $entityId;
        $this->operationType = $operationType;
        $this->entityType = $entityType;
    }

    /**
     * @return string
     */
    public function getEntityId(): string
    {
        return $this->entityId;
    }

    /**
     * @return EventNotificationOperationType
     */
    public function getOperationType(): EventNotificationOperationType
    {
        return $this->operationType;
    }

    /**
     * @return EventNotificationEntityType
     */
    public function getEntityType(): EventNotificationEntityType
    {
        return $this->entityType;
    }
}