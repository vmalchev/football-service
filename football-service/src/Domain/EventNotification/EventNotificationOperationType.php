<?php


namespace Sportal\FootballApi\Domain\EventNotification;


use MyCLabs\Enum\Enum;

/**
 * @method static EventNotificationOperationType CREATE()
 * @method static EventNotificationOperationType UPDATE()
 * @method static EventNotificationOperationType DELETE()
 */
class EventNotificationOperationType extends Enum
{
    const CREATE = 'create';
    const UPDATE = 'update';
    const DELETE = 'delete';
}
