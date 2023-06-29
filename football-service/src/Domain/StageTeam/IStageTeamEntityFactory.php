<?php


namespace Sportal\FootballApi\Domain\StageTeam;


interface IStageTeamEntityFactory
{

    public function setTeamId(string $teamId): IStageTeamEntityFactory;

    public function create(): IStageTeamEntity;

    public function setStageId(string $stageId): IStageTeamEntityFactory;

}