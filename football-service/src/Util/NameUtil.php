<?php
namespace Sportal\FootballApi\Util;

class NameUtil
{

    private static $classNameMap = [];

    public static function camel2underscore($camelCase)
    {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1_', $camelCase));
    }

    public static function shortClassName($qualifiedClass)
    {
        if (($shortClass = strrchr($qualifiedClass, '\\')) !== false) {
            return substr($shortClass, 1);
        }
        return $qualifiedClass;
    }

    public static function persistanceName($className)
    {
        if (isset(static::$classNameMap[$className])) {
            $name = static::$classNameMap[$className];
        } else {
            $name = static::camel2underscore(static::shortClassName($className));
            static::$classNameMap[$className] = $name;
        }
        return $name;
    }

    public static function normalizeName($value)
    {
        return iconv('UTF-8', 'ASCII//TRANSLIT', $value);
    }

    public static function getMatchingCount(array $existing, array $matchWith)
    {
        $matched = 0;
        foreach ($existing as $exName) {
            foreach ($matchWith as $matchName) {
                if (static::nameMatch($exName, $matchName)) {
                    $matched ++;
                }
            }
        }
        return $matched;
    }

    public static function isCyrillic($name)
    {
        return preg_match('/[\p{Cyrillic}]/u', $name);
    }

    public static function nameMatch($name1, $name2)
    {
        $dist = levenshtein(strtolower($name1), strtolower($name2));
        return ($dist === 0 || $dist === 1);
    }

    public static function personNameMatch($name1, $name2)
    {
        $existingNames = array_map('static::normalizeName', explode(' ', str_replace('-', ' ', $name1)));
        
        $matchNames = array_map('static::normalizeName', explode(' ', str_replace('-', ' ', $name2)));
        
        $matched = NameUtil::getMatchingCount($existingNames, $matchNames);
        
        if ((count($existingNames) >= 2 && $matched >= 2) || (count($existingNames) == 1 && $matched == 1)) {
            return true;
        }
        
        return false;
    }

    public static function escapeTsQuery($value)
    {
        return trim(
            str_replace(
                [
                    '&',
                    '|',
                    '!',
                    ':',
                    '*'
                ],
                [
                    '',
                    '',
                    '',
                    '',
                    ''
                ], $value));
    }

    public static function getTsArr($name)
    {
        $arrPlayerNames = explode(" ", $name);
        $names = [];
        foreach ($arrPlayerNames as $pName) {
            if (! empty($pName)) {
                $names[] = NameUtil::escapeTsQuery($pName) . ':*';
            }
        }
        return $names;
    }

    public static function findNameMatch(array $list, $match)
    {
        foreach ($list as $existing) {
            
            $existingNames = array_map('static::normalizeName',
                explode(' ', str_replace('-', ' ', $existing->getName())));
            
            $matchNames = array_map('static::normalizeName', explode(' ', str_replace('-', ' ', $match->getName())));
            
            $matched = NameUtil::getMatchingCount($existingNames, $matchNames);
            
            if ((count($existingNames) >= 2 && $matched >= 2) || (count($existingNames) == 1 && $matched == 1)) {
                return $existing;
            }
        }
        
        return null;
    }
}