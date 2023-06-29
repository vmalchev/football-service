<?php


namespace Sportal\FootballApi\Domain\EventNotification;


interface IEventNotificationEntity
{
    /**
     * @return string
     */
    public function getEntityId(): string;

    /**
     * @return EventNotificationOperationType
     */
    public function getOperationType(): EventNotificationOperationType;

    /**
     * @return EventNotificationEntityType
     */
    public function getEntityType(): EventNotificationEntityType;
}