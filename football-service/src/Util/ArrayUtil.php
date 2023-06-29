<?php
namespace Sportal\FootballApi\Util;

class ArrayUtil
{

    public static function getMerged($array1, $array2)
    {
        if (! empty($array1) && ! empty($array2)) {
            return array_merge($array1, $array2);
        } elseif (empty($array2)) {
            return $array1;
        } else {
            return $array2;
        }
    }

    public static function indexMulti(array $items, callable $identifier)
    {
        $index = [];
        foreach ($items as $item) {
            $id = $identifier($item);
            if (! isset($index[$id])) {
                $index[$id] = [];
            }
            $index[$id][] = $item;
        }
        return $index;
    }

    public static function toMap(array $items, callable $identifier)
    {
        return self::indexSingle($items, $identifier);
    }

    public static function indexSingle(array $items, callable $identifier)
    {
        $index = [];
        foreach ($items as $item) {
            $id = $identifier($item);
            $index[$id] = $item;
        }
        return $index;
    }
}