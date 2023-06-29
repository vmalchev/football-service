<?php

namespace Sportal\FootballApi\Domain\StageTeam;

use Sportal\FootballApi\Application\Exception\DuplicateKeyException;
use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Domain\Team\ITeamRepository;

class StageTeamRule implements IStageTeamRule
{

    private ITeamRepository $teamRepository;

    public function __construct(ITeamRepository $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    /**
     * @inheritDoc
     */
    public function validate(array $stageTeamEntities)
    {
        $teamIds = [];
        foreach ($stageTeamEntities as $stageTeamEntity) {
            $teamIds[] = $stageTeamEntity->getTeamId();
        }

        if (array_unique($teamIds) !== $teamIds) {
            $duplicates = array_diff_assoc($teamIds, array_unique($teamIds));
            throw new DuplicateKeyException('team_id ' . implode(',', $duplicates));
        }

        foreach ($teamIds as $teamId) {
            if (!$this->teamRepository->exists($teamId)) {
                throw new NoSuchEntityException('Team ' . $teamId);
            }
        }
    }

}