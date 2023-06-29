<?php


namespace Sportal\FootballApi\Domain\Team;


use Sportal\FootballApi\Domain\Coach\ICoachEntity;
use Sportal\FootballApi\Domain\Season\ISeasonEntity;

class TeamProfile implements ITeamProfile
{
    /**
     * @var ITeamEntity
     */
    private ITeamEntity $teamEntity;

    /**
     * @var ICoachEntity
     */
    private ?ICoachEntity $coachEntity;

    /**
     * @var ISeasonEntity[]
     */
    private array $activeSeasons = [];

    public function getTeamEntity(): ITeamEntity
    {
        return $this->teamEntity;
    }

    public function getCurrentCoach(): ?ICoachEntity
    {
        return $this->coachEntity;
    }

    public function setTeamEntity(ITeamEntity $teamEntity): ITeamProfile
    {
        $teamProfile = clone $this;
        $teamProfile->teamEntity = $teamEntity;

        return $teamProfile;
    }

    public function setCurrentCoach(?ICoachEntity $coachEntity): ITeamProfile
    {
        $teamProfile = clone $this;
        $teamProfile->coachEntity = $coachEntity;

        return $teamProfile;
    }

    /**
     * @inheritDoc
     */
    public function setActiveSeasons(array $seasons): ITeamProfile
    {
        $teamProfile = clone $this;
        $teamProfile->activeSeasons = $seasons;
        return $teamProfile;
    }

    /**
     * @inheritDoc
     */
    public function getActiveSeasons(): array
    {
        return $this->activeSeasons;
    }
}