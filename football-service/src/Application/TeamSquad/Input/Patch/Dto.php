<?php


namespace Sportal\FootballApi\Application\TeamSquad\Input\Patch;

use Sportal\FootballApi\Application\IDto;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @SWG\Definition(definition="v2_TeamSquadInput")
 */
class Dto implements IDto
{
    /**
     * @var string|null
     */
    private ?string $team_id;

    /**
     * @SWG\Property(property="players")
     * @var PlayerDto[]|null
     */
    private ?array $players;

    /**
     * Dto constructor.
     * @param string|null $team_id
     * @param PlayerDto[]|null $players
     */
    public function __construct(?string $team_id = null, ?array $players = null)
    {
        $this->team_id = $team_id;
        $this->players = $players;
    }

    /**
     * @param string|null $team_id
     * @return Dto
     */
    public function setTeamId(string $team_id): Dto
    {
        $dto = clone $this;
        $dto->team_id = $team_id;
        return $dto;
    }

    /**
     * @return string|null
     */
    public function getTeamId(): ?string
    {
        return $this->team_id;
    }

    /**
     * @return PlayerDto[]|null
     */
    public function getPlayers(): ?array
    {
        return $this->players;
    }

    public static function getValidatorConstraints(): Assert\Collection
    {
        return new Assert\Collection([
            'players' => new Assert\Optional([
                new Assert\Type('array'),
                new Assert\All([PlayerDto::getValidatorConstraints()])
            ])
        ]);
    }
}