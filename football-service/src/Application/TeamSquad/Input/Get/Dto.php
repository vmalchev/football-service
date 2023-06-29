<?php


namespace Sportal\FootballApi\Application\TeamSquad\Input\Get;


use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Domain\TeamSquad\TeamSquadStatus;

class Dto implements IDto
{
    /**
     * @var string
     */
    private string $teamId;

    /**
     * @var MemberStatus
     */
    private MemberStatus $status;

    /**
     * @param string $teamId
     * @param MemberStatus $status
     */
    public function __construct(string $teamId, MemberStatus $status)
    {
        $this->teamId = $teamId;
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getTeamId(): string
    {
        return $this->teamId;
    }

    /**
     * @return MemberStatus
     */
    public function getStatus(): MemberStatus
    {
        return $this->status;
    }
}