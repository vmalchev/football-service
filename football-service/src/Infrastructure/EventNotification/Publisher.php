<?php


namespace Sportal\FootballApi\Infrastructure\EventNotification;


use Bschmitt\Amqp\Amqp;
use Sportal\FootballApi\Domain\EventNotification\IEventNotificationEntity;
use Sportal\FootballApi\Domain\EventNotification\IPublisher;

class Publisher implements IPublisher
{
    const ROUTING_KEY = 'football.object';

    private Amqp $amqp;

    private ?string $queueName;

    private ?string $isEnabled;

    public function __construct(Amqp $amqp)
    {
        $this->amqp = $amqp;
        $this->queueName = env('NOTIFICATION_QUEUE_NAME');
        $this->isEnabled = env('NOTIFICATION_QUEUE_ENABLED');
    }

    /**
     * @param IEventNotificationEntity $eventNotificationEntity
     */
    public function publish(IEventNotificationEntity $eventNotificationEntity): void
    {
        if (!$this->isEnabled || !$this->queueName) {
            return;
        }

        $message = json_encode(
            [
                'object' => $eventNotificationEntity->getEntityType()->getValue(),
                'id' => $eventNotificationEntity->getEntityId(),
                'operation' => $eventNotificationEntity->getOperationType()->getValue(),
                'timestamp' => (new \DateTime())->format(\DateTime::ISO8601),
            ]
        );

        try {
            $this->amqp->publish(
                self::ROUTING_KEY,
                $message,
                ['queue' => $this->queueName]
            );
        } catch (\Exception $exception) {

        }
    }
}