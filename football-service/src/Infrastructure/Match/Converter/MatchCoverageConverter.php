<?php


namespace Sportal\FootballApi\Infrastructure\Match\Converter;


use Sportal\FootballApi\Domain\Match\MatchCoverage;

class MatchCoverageConverter
{
    public static function fromValue($value): ?MatchCoverage
    {
        if ($value === null) {
            return MatchCoverage::UNKNOWN();
        } else if ($value) {
            return MatchCoverage::LIVE();
        } else {
            return MatchCoverage::NOT_LIVE();
        }
    }

    public static function toValue(?MatchCoverage $coverage): ?bool
    {
        if ($coverage === null || MatchCoverage::UNKNOWN()->equals($coverage)) {
            return null;
        }
        return $coverage->equals(MatchCoverage::LIVE());
    }
}