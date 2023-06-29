<?php


namespace Sportal\FootballApi\Infrastructure\MatchEvent;


use Sportal\FootballApi\Domain\MatchEvent\TeamPositionStatus;

class TeamPositionStatusConverter
{
    public static function fromValue($value): TeamPositionStatus
    {
        if ($value) {
            return TeamPositionStatus::HOME();
        } else {
            return TeamPositionStatus::AWAY();
        }
    }

    public static function toValue(TeamPositionStatus $status)
    {
        if ($status->getValue() == TeamPositionStatus::HOME()->getValue()) {
            return 1;
        } else {
            return 0;
        }
    }
}