<?php


namespace Sportal\FootballApi\Infrastructure\Mapper;


use JsonSerializable;

class JsonSerializablePropertyResolver implements IPropertyResolver
{
    public function resolve(object $object): array
    {
        $properties = [];
        if ($object instanceof JsonSerializable) {
            $jsonSerialized = $object->jsonSerialize();
            if (is_array($jsonSerialized)) {
                foreach ($jsonSerialized as $value) {
                    if (is_object($value) || is_array($value)) {
                        $properties[] = $value;
                    }
                }
            }
        }
        return $properties;
    }
}