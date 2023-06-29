<?php


namespace Sportal\FootballApi\Infrastructure\EventNotification;

use Bschmitt\Amqp\Facades\Amqp;
use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\Around;

use Sportal\FootballApi\Application\AOP\AttachEventNotification;
use Sportal\FootballApi\Application\IEventNotificationable;
use Sportal\FootballApi\Domain\EventNotification\EventNotificationEntityType;
use Sportal\FootballApi\Domain\EventNotification\EventNotificationOperationType;
use Sportal\FootballApi\Domain\EventNotification\IEventNotificationEntityFactory;
use Sportal\FootballApi\Domain\EventNotification\IPublisher;


class AMQPNotificationAspect implements Aspect
{
    /**
     * @Around("@execution(Sportal\FootballApi\Application\AOP\AttachEventNotification)")
     * @param MethodInvocation $invocation Invocation
     * @return IEventNotificationable
     * @see IService
     */
    public function aroundServiceMethod(MethodInvocation $invocation)
    {
        $dto = $invocation->proceed();

        $entityType = $invocation->getMethod()->getAnnotation(AttachEventNotification::class)->object;
        $operationType = $invocation->getMethod()->getAnnotation(AttachEventNotification::class)->operation;

        if (!EventNotificationOperationType::isValid($operationType) ||
            !EventNotificationEntityType::isValid($entityType) ||
            !$dto instanceof IEventNotificationable
        ) {
            return $dto;
        }

        $publisher = app()->make(IPublisher::class);
        $eventNotificationEntityFactory = app()->make(IEventNotificationEntityFactory::class);

        $eventNotificationEntity = $eventNotificationEntityFactory
            ->setEntityType(new EventNotificationEntityType($entityType))
            ->setEntityId($dto->getId())
            ->setOperationType(new EventNotificationOperationType($operationType))
            ->create();

        $publisher->publish($eventNotificationEntity);

        return $dto;
    }
}