<?php


namespace Sportal\FootballApi\Infrastructure\AOP;


use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Go\Aop\Support\PointcutBuilder;
use Go\Core\AspectKernel;
use Go\Core\AspectContainer;
use Sportal\FootballApi\Infrastructure\EventNotification\AMQPNotificationAspect;

/**
 * Application Aspect Kernel
 */
class ApplicationAspectKernel extends AspectKernel
{
    /**
     * Configure an AspectContainer with advisors, aspects and pointcuts
     * @param AspectContainer $container
     * @return void
     */
    protected function configureAop(AspectContainer $container)
    {
        $container->registerAspect(new AssetAspect());
        $container->registerAspect(new AMQPNotificationAspect());
    }
}