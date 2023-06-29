<?php
namespace Sportal\FootballApi\Cache;

class SortedLexSet extends SortedSet
{

    const SCORE_SEPARATOR = ":";

    public function replace(array $membersScoreDict)
    {
        $dict = [];
        foreach ($membersScoreDict as $key => $score) {
            if (strpos($score, static::SCORE_SEPARATOR) !== false) {
                throw new \InvalidArgumentException(
                    "A lex score must not contain '" . static::SCORE_SEPARATOR . "' found in: " . $score . " for key: " .
                         $key);
            }
            $dict[$score . ":" . $key] = 0;
        }
        
        $this->setAll($dict);
    }

    public function getAll()
    {
        $all = parent::getAll();
        if ($all !== null) {
            $keys = [];
            foreach ($all as $member) {
                $keys[] = explode(static::SCORE_SEPARATOR, $member, 2)[1];
            }
            return $keys;
        }
        return null;
    }
}