<?php


namespace Sportal\FootballApi\Application\AOP;


use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
class AttachEventNotification extends Annotation
{
    /**
     * Object type
     *
     * @var string
     */
    public $object;

    /**
     * Operation type
     *
     * @var string
     */
    public $operation;
}