<?php


namespace Sportal\FootballApi\Domain\Stage;


use Sportal\FootballApi\Domain\Season\ISeasonEntity;

interface IStageRule
{

    public function validate(ISeasonEntity $seasonEntity, array $stageEntities);

}