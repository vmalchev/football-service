<?php


namespace Sportal\FootballApi\Domain\Team;


use Sportal\FootballApi\Domain\Coach\ICoachRepository;
use Sportal\FootballApi\Domain\Season\ISeasonRepository;
use Sportal\FootballApi\Domain\Season\SeasonFilter;
use Sportal\FootballApi\Domain\Season\SeasonStatus;

class TeamProfileBuilder implements ITeamProfileBuilder
{
    /**
     * @var ITeamProfile
     */
    private ITeamProfile $teamProfile;

    /**
     * @var ICoachRepository
     */
    private ICoachRepository $coachRepository;

    /**
     * @var ISeasonRepository
     */
    private ISeasonRepository $seasonRepository;

    /**
     * @param ITeamProfile $teamProfile
     * @param ICoachRepository $coachRepository
     * @param ISeasonRepository $seasonRepository
     */
    public function __construct(
        ITeamProfile     $teamProfile,
        ICoachRepository $coachRepository,
        ISeasonRepository $seasonRepository
    ) {
        $this->teamProfile = $teamProfile;
        $this->coachRepository = $coachRepository;
        $this->seasonRepository = $seasonRepository;
    }

    /**
     * @param ITeamEntity $teamEntity
     * @return ITeamProfile
     */
    public function build(ITeamEntity $teamEntity): ITeamProfile
    {
        $coach = $this->coachRepository->findCurrentCoachByTeam($teamEntity);

        $seasonFilter = SeasonFilter::create()->setStatus(SeasonStatus::ACTIVE())->setTeamId($teamEntity->getId());

        $seasons = $this->seasonRepository->listByFilter($seasonFilter);

        return $this->teamProfile
            ->setTeamEntity($teamEntity)
            ->setCurrentCoach($coach)
            ->setActiveSeasons($seasons);
    }
}