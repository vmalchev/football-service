<?php
namespace Sportal\FootballApi\Cache\Serializer;

interface SerializerInterface
{

    public function serialize($value);

    public function unserialize($string);
}