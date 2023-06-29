<?php


namespace Sportal\FootballApi\Domain\TeamSquad\Specification;


use Sportal\FootballApi\Domain\TeamSquad\Exception\InvalidTeamSquadException;
use Sportal\FootballApi\Domain\TeamSquad\ITeamPlayerEntity;
use Sportal\FootballApi\Domain\TeamSquad\TeamSquadStatus;

class TeamPlayerSpecification
{
    /**
     * @param ITeamPlayerEntity[] $teamPlayers
     * @throws InvalidTeamSquadException
     */
    public function validate(array $teamPlayers)
    {
        $activePlayerIds = [];
        foreach ($teamPlayers as $teamPlayer) {
            if ($teamPlayer->getStatus()->getValue() == TeamSquadStatus::ACTIVE()->getValue()) {
                $activePlayerIds[] = $teamPlayer->getPlayerId();
            }
        }
        if (count($activePlayerIds) != count(array_unique($activePlayerIds))) {
            throw new InvalidTeamSquadException("Duplicate currently active players");
        }
    }
}