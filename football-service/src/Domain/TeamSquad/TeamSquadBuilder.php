<?php


namespace Sportal\FootballApi\Domain\TeamSquad;


use Sportal\FootballApi\Domain\TeamSquad\ITeamPlayerRepository;
use Sportal\FootballApi\Domain\Team\ITeamEntity;

class TeamSquadBuilder
{
    private ITeamPlayerRepository $teamPlayerRepository;

    private ITeamSquad $teamSquad;

    /**
     * TeamSquadBuilder constructor.
     * @param ITeamPlayerRepository $teamPlayerRepository
     * @param ITeamSquad $teamSquad
     */
    public function __construct(ITeamPlayerRepository $teamPlayerRepository, ITeamSquad $teamSquad)
    {
        $this->teamPlayerRepository = $teamPlayerRepository;
        $this->teamSquad = $teamSquad;
    }

    /**
     * @param ITeamEntity $team
     * @param TeamSquadStatus|null $status
     * @return ITeamSquad
     */
    public function build(ITeamEntity $team, ?TeamSquadStatus $status = null): ITeamSquad
    {
        $players = $this->teamPlayerRepository->findByTeam($team, $status);
        return $this->teamSquad->setTeam($team)
            ->setPlayers($players);
    }
}