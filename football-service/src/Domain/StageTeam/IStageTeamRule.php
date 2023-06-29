<?php

namespace Sportal\FootballApi\Domain\StageTeam;

use Sportal\FootballApi\Application\Exception\DuplicateKeyException;
use Sportal\FootballApi\Application\Exception\NoSuchEntityException;

interface IStageTeamRule
{

    /**
     * @param IStageTeamEntity[] $stageTeamEntities
     * @throws DuplicateKeyException|NoSuchEntityException
     */
    public function validate(array $stageTeamEntities);

}