<?php


namespace Sportal\FootballApi\Infrastructure\Season;


use Sportal\FootballApi\Domain\Season\SeasonStatus;

class StatusDatabaseConverter
{
    public static function fromValue($value): SeasonStatus
    {
        if ($value) {
            return SeasonStatus::ACTIVE();
        } else if ($value !== null) {
            return SeasonStatus::INACTIVE();
        }
    }

    public static function toValue(SeasonStatus $status)
    {
        if ($status->getValue() == SeasonStatus::ACTIVE()->getValue()) {
            return 1;
        } else {
            return 0;
        }
    }
}