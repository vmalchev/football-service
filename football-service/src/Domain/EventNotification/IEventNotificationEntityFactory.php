<?php


namespace Sportal\FootballApi\Domain\EventNotification;


interface IEventNotificationEntityFactory
{
    /**
     * @param string $entityId
     * @return IEventNotificationEntityFactory
     */
    public function setEntityId(string $entityId): IEventNotificationEntityFactory;

    /**
     * @param EventNotificationOperationType $operationType
     * @return IEventNotificationEntityFactory
     */
    public function setOperationType(EventNotificationOperationType $operationType): IEventNotificationEntityFactory;

    /**
     * @param EventNotificationEntityType $entityType
     * @return IEventNotificationEntityFactory
     */
    public function setEntityType(EventNotificationEntityType $entityType): IEventNotificationEntityFactory;

    public function setEntity(IEventNotificationEntity $eventNotificationEntity): IEventNotificationEntityFactory;

    public function getEventNotificationEntityFactory(): IEventNotificationEntityFactory;

    public function create(): IEventNotificationEntity;
}