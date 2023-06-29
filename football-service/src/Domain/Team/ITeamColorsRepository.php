<?php


namespace Sportal\FootballApi\Domain\Team;


interface ITeamColorsRepository
{

    /**
     * @param ITeamColorsEntity $teamColorsEntity
     * @return ITeamColorsEntity
     */
    public function insert(ITeamColorsEntity $teamColorsEntity): ITeamColorsEntity;

    /**
     * @param ITeamColorsEntity $teamColorsEntity
     * @return ITeamColorsEntity
     */
    public function update(ITeamColorsEntity $teamColorsEntity): ITeamColorsEntity;

    /**
     * @param string $entityId
     * @param string $entityType
     * @return bool
     */
    public function exists(string $entityId, string $entityType): bool;

    /**
     * @param ITeamColorsEntity $teamColorsEntity
     * @return ITeamColorsEntity
     */
    public function upsert(ITeamColorsEntity $teamColorsEntity): ITeamColorsEntity;
}