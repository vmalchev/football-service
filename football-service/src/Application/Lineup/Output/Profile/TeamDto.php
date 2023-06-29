<?php


namespace Sportal\FootballApi\Application\Lineup\Output\Profile;


use JsonSerializable;
use Sportal\FootballApi\Application\Coach;
use Sportal\FootballApi\Application\IDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_LineupTeamDto")
 */
class TeamDto implements IDto, JsonSerializable
{
    /**
     * @var string|null
     * @SWG\Property(property="formation")
     */
    private ?string $formation;

    /**
     * @var Coach\Output\Get\Dto|null
     * @SWG\Property(property="coach")
     */
    private ?Coach\Output\Get\Dto $coach;

    /**
     * @var string|null
     * @SWG\Property(property="team_id")
     */
    private ?string $team_id;

    /**
     * @var PlayerDto[]
     * @SWG\Property(property="players")
     */
    private ?array $players;

    /**
     * TeamDto constructor.
     * @param string|null $formation
     * @param Coach\Output\Get\Dto|null $coach
     * @param string|null $team_id
     * @param PlayerDto[]|null $players
     */
    public function __construct(?string $formation, ?Coach\Output\Get\Dto $coach, ?string $team_id, ?array $players)
    {
        $this->formation = $formation;
        $this->coach = $coach;
        $this->team_id = $team_id;
        $this->players = $players;
    }

    /**
     * @return string|null
     */
    public function getFormation(): ?string
    {
        return $this->formation;
    }

    /**
     * @return Coach\Output\Get\Dto|null
     */
    public function getCoach(): ?Coach\Output\Get\Dto
    {
        return $this->coach;
    }

    /**
     * @return string|null
     */
    public function getTeamId(): ?string
    {
        return $this->team_id;
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