<?php


namespace Sportal\FootballApi\Application\PlayerStatistic\Input\Get;


use Sportal\FootballApi\Application\IDto;

class Dto implements IDto, \JsonSerializable
{
    /**
     * @var array|null
     */
    private ?array $playerIds;

    /**
     * @var array|null
     */
    private ?array $seasonIds;

    /**
     * @var string|null
     */
    private ?string $teamId;

    /**
     * @param array|null $playerIds
     * @param array|null $seasonIds
     * @param string|null $teamId
     */
    public function __construct(
        ?array $playerIds,
        ?array $seasonIds,
        ?string $teamId
    ) {
        $this->playerIds = $playerIds;
        $this->seasonIds = $seasonIds;
        $this->teamId = $teamId;
    }

    /**
     * @return array|null
     */
    public function getPlayerIds(): ?array
    {
        return $this->playerIds;
    }

    /**
     * @return array|null
     */
    public function getSeasonIds(): ?array
    {
        return $this->seasonIds;
    }

    /**
     * @return string
     */
    public function getTeamId(): ?string
    {
        return $this->teamId;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}