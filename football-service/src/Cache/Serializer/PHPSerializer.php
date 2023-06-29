<?php
namespace Sportal\FootballApi\Cache\Serializer;

class PHPSerializer implements SerializerInterface
{

    /**
     * {@inheritDoc}
     * @see SerialzerInterface::serialize()
     */
    public function serialize($value)
    {
        return serialize($value);
    }

    /**
     * {@inheritDoc}
     * @see SerialzerInterface::unserialize()
     */
    public function unserialize($string)
    {
        return unserialize($string);
    }
}