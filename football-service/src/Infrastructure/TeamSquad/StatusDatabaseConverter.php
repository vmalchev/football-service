<?php


namespace Sportal\FootballApi\Infrastructure\TeamSquad;


use Sportal\FootballApi\Domain\TeamSquad\TeamSquadStatus;

class StatusDatabaseConverter
{
    public static function fromValue($value): TeamSquadStatus
    {
        if ($value) {
            return TeamSquadStatus::ACTIVE();
        } else if ($value !== null) {
            return TeamSquadStatus::INACTIVE();
        }
    }

    public static function toValue(TeamSquadStatus $status)
    {
        if ($status->getValue() == TeamSquadStatus::ACTIVE()->getValue()) {
            return 1;
        } else {
            return 0;
        }
    }
}