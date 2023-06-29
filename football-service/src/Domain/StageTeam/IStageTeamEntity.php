<?php


namespace Sportal\FootballApi\Domain\StageTeam;


interface IStageTeamEntity
{

    public function getStageId(): string;

    public function getTeamId(): string;

}