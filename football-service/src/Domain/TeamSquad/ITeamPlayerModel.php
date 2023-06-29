<?php


namespace Sportal\FootballApi\Domain\TeamSquad;

use Sportal\FootballApi\Domain\Team\ITeamEntity;

interface ITeamPlayerModel
{
    public function upsert(): ITeamPlayerModel;

    public function withBlacklist(): ITeamPlayerModel;

    /**
     * @param ITeamPlayerEntity[] $players
     * @return ITeamPlayerModel
     */
    public function setTeamPlayers(array $players): ITeamPlayerModel;

    /**
     * @param ITeamEntity $teamEntity
     * @return ITeamPlayerModel
     */
    public function setTeamEntity(ITeamEntity $teamEntity): ITeamPlayerModel;

    /**
     * @return ITeamPlayerEntity[]
     */
    public function getPlayers(): array;

    /**
     * @return ITeamPlayerEntity[]
     */
    public function getActivePlayers(): array;

    /**
     * @param bool $upsertByTeam
     * @return ITeamPlayerModel
     */
    public function setUpsertByTeam(bool $upsertByTeam): ITeamPlayerModel;
}