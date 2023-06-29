<?php

namespace Sportal\FootballApi\Domain\Stage;


interface IStageDeleteRule
{

    public function validate(IStageEntity $stageEntity);

}