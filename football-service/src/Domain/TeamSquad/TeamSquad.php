<?php

namespace Sportal\FootballApi\Domain\TeamSquad;


use Sportal\FootballApi\Domain\Team\ITeamEntity;

class TeamSquad implements ITeamSquad
{
    private ITeamEntity $team;

    /**
     * @var ITeamPlayerEntity[]
     */
    private array $teamPlayers;

    /**
     * @return ITeamEntity
     */
    public function getTeam(): ITeamEntity
    {
        return $this->team;
    }

    /**
     * @param ITeamEntity $team
     * @return TeamSquad
     */
    public function setTeam(ITeamEntity $team): TeamSquad
    {
        $squad = clone $this;
        $squad->team = $team;
        return $squad;
    }

    /**
     * @return ITeamPlayerEntity[]
     */
    public function getPlayers(): array
    {
        return $this->teamPlayers;
    }

    /**
     * @param ITeamPlayerEntity[] $teamPlayers
     * @return TeamSquad
     */
    public function setPlayers(array $teamPlayers): TeamSquad
    {
        $squad = clone $this;
        $squad->teamPlayers = $teamPlayers;
        return $squad;
    }
}