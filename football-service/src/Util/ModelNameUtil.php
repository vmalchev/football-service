<?php
namespace Sportal\FootballApi\Util;

class ModelNameUtil
{

    private $persitanceNameMap;

    public function persistanceName($model)
    {
        $className = $this->getClass($model);
        if (! isset($this->persitanceNameMap[$className])) {
            $this->persitanceNameMap[$className] = $this->camel2underscore($this->shortName($className));
        }
        return $this->persitanceNameMap[$className];
    }

    public function shortClassName($model)
    {
        return $this->shortName($this->getClass($model));
    }

    public function camel2underscore($camelCase)
    {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1_', $camelCase));
    }

    private function getClass($model)
    {
        if (is_object($model)) {
            return get_class($model);
        }
        return $model;
    }

    private function shortName($qualifiedClass)
    {
        if (($shortClass = strrchr($qualifiedClass, '\\')) !== false) {
            return substr($shortClass, 1);
        }
        return $qualifiedClass;
    }
}