<?php


namespace Sportal\FootballApi\Application\TeamSquad\Output\Get;

use Sportal\FootballApi\Application\Player;
use Sportal\FootballApi\Application\Team;
use Sportal\FootballApi\Domain\TeamSquad\ITeamSquad;


class Mapper
{
    private Player\Output\Get\Mapper $playerMapper;

    private Team\Output\Get\Mapper $teamMapper;

    /**
     * Mapper constructor.
     * @param Player\Output\Get\Mapper $playerMapper
     * @param Team\Output\Get\Mapper $teamMapper
     */
    public function __construct(Player\Output\Get\Mapper $playerMapper, Team\Output\Get\Mapper $teamMapper)
    {
        $this->playerMapper = $playerMapper;
        $this->teamMapper = $teamMapper;
    }


    public function map(ITeamSquad $teamSquad): Dto
    {
        $players = [];
        foreach ($teamSquad->getPlayers() as $teamPlayer) {
            $players[] = new PlayerDto(
                $this->playerMapper->map($teamPlayer->getPlayer()),
                $teamPlayer->getStatus()->getKey(),
                $teamPlayer->getContractType()->getKey(),
                $teamPlayer->getStartDate() ? $teamPlayer->getStartDate()->format('Y-m-d') : null,
                $teamPlayer->getEndDate() ? $teamPlayer->getEndDate()->format('Y-m-d') : null,
                $teamPlayer->getShirtNumber()
            );
        }
        $team = $this->teamMapper->map($teamSquad->getTeam());
        return new Dto($team,
            $players);
    }
}