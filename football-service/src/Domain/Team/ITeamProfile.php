<?php


namespace Sportal\FootballApi\Domain\Team;


use Sportal\FootballApi\Domain\Coach\ICoachEntity;
use Sportal\FootballApi\Domain\Season\ISeasonEntity;

interface ITeamProfile
{
    /**
     * @return ITeamEntity
     */
    public function getTeamEntity(): ITeamEntity;

    /**
     * @return ICoachEntity
     */
    public function getCurrentCoach(): ?ICoachEntity;

    /**
     * @param ITeamEntity $teamEntity
     * @return ITeamProfile
     */
    public function setTeamEntity(ITeamEntity $teamEntity): ITeamProfile;

    /**
     * @param ICoachEntity|null $coachEntity
     * @return ITeamProfile
     */
    public function setCurrentCoach(?ICoachEntity $coachEntity): ITeamProfile;

    /**
     * @param ISeasonEntity[] $seasons
     * @return ITeamProfile
     */
    public function setActiveSeasons(array $seasons): ITeamProfile;

    /**
     * @return ISeasonEntity[]
     */
    public function getActiveSeasons(): array;
}