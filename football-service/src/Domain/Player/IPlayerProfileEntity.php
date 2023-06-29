<?php


namespace Sportal\FootballApi\Domain\Player;


use Sportal\FootballApi\Domain\TeamSquad\ITeamPlayerEntity;

interface IPlayerProfileEntity
{

    /**
     * @return IPlayerEntity
     */
    public function getPlayerEntity(): IPlayerEntity;

    /**
     * @return ITeamPlayerEntity[]
     */
    public function getTeamPlayerEntities(): array;

    /**
     * @param IPlayerEntity $playerEntity
     * @return IPlayerProfileEntity
     */
    public function setPlayerEntity(IPlayerEntity $playerEntity): IPlayerProfileEntity;

    /**
     * @param ITeamPlayerEntity[] $teamEntities
     * @return IPlayerProfileEntity
     */
    public function setTeamPlayerEntities(array $teamEntities): IPlayerProfileEntity;
}