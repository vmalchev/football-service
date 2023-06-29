<?php
namespace Sportal\FootballApi\Cache\Index;

use Sportal\FootballApi\Cache\Cache;

trait GeneratesIndexName
{

    public function getColumnIndex($name, $value)
    {
        return $name . Cache::DELIM . $value;
    }

    public function getMultiIndex(array $nameValueMap)
    {
        $values = [];
        foreach ($nameValueMap as $name => $value) {
            $values[] = $this->getColumnIndex($name, $value);
        }
        return implode(Cache::DELIM, $values);
    }
}