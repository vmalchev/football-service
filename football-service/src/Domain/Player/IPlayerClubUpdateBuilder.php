<?php


namespace Sportal\FootballApi\Domain\Player;


use Sportal\FootballApi\Domain\Player\Exception\InvalidPlayerTeamException;
use Sportal\FootballApi\Domain\TeamSquad\ITeamPlayerEntity;
use Sportal\FootballApi\Domain\TeamSquad\ITeamPlayerModel;

interface IPlayerClubUpdateBuilder
{

    /**
     * @param IPlayerEntity $playerEntity
     * @param ITeamPlayerEntity[]
     * @return ITeamPlayerModel
     * @throws InvalidPlayerTeamException
     */
    public function build(IPlayerEntity $playerEntity, array $newClubs): ITeamPlayerModel;
}