<?php


namespace Sportal\FootballApi\Domain\EventNotification;


interface IPublisher
{
    public function publish(IEventNotificationEntity $eventNotificationEntity): void;
}