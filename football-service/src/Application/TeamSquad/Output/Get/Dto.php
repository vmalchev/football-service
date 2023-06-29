<?php


namespace Sportal\FootballApi\Application\TeamSquad\Output\Get;


use JsonSerializable;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\Team;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_TeamSquad", required={"team", "players"})
 */
class Dto implements JsonSerializable, IDto
{

    /**
     * @var Team\Output\Get\Dto
     * @SWG\Property(property="team")
     */
    private Team\Output\Get\Dto $team;

    /**
     * @var PlayerDto[]
     * @SWG\Property(property="players")
     */
    private array $players;

    /**
     * Dto constructor.
     * @param Team\Output\Get\Dto $team
     * @param PlayerDto[] $players
     */
    public function __construct(Team\Output\Get\Dto $team, array $players)
    {
        $this->team = $team;
        $this->players = $players;
    }

    /**
     * @return Team\Output\Get\Dto
     */
    public function getTeam(): Team\Output\Get\Dto
    {
        return $this->team;
    }

    /**
     * @return PlayerDto[]
     */
    public function getPlayers(): array
    {
        return $this->players;
    }


    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}