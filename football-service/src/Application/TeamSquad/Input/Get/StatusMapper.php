<?php


namespace Sportal\FootballApi\Application\TeamSquad\Input\Get;

use Sportal\FootballApi\Domain\TeamSquad\TeamSquadStatus;

class StatusMapper
{
    public function map(MemberStatus $status): ?TeamSquadStatus
    {
        if (MemberStatus::ACTIVE()->equals($status)) {
            return TeamSquadStatus::ACTIVE();
        } else if (MemberStatus::INACTIVE()->equals($status)) {
            return TeamSquadStatus::INACTIVE();
        } else {
            return null;
        }
    }

}