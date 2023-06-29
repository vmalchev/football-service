<?php


namespace Sportal\FootballApi\Domain\PlayerStatistic;


class PlayerSeasonStatisticFilter
{

    private ?array $seasonIds = [];

    private ?array $playerIds = [];

    private ?string $teamId = null;

    /**
     * @return array|null
     */
    public function getSeasonIds(): ?array
    {
        return $this->seasonIds;
    }

    /**
     * @return array|null
     */
    public function getPlayerIds(): ?array
    {
        return $this->playerIds;
    }

    /**
     * @return string|null
     */
    public function getTeamId(): ?string
    {
        return $this->teamId;
    }

    /**
     * @param array|null $seasonId
     * @return PlayerSeasonStatisticFilter
     */
    public function setSeasonIds(?array $seasonId): self
    {
        $this->seasonIds = $seasonId;
        return $this;
    }

    /**
     * @param array|null $playerId
     * @return PlayerSeasonStatisticFilter
     */
    public function setPlayerIds(?array $playerId): self
    {
        $this->playerIds = $playerId;
        return $this;
    }

    /**
     * @param string|null $teamId
     * @return PlayerSeasonStatisticFilter
     */
    public function setTeamId(?string $teamId): self
    {
        $this->teamId = $teamId;
        return $this;
    }

    public function isEmpty(): bool
    {
        foreach (get_object_vars($this) as $property) {
            if (!empty($property)) {
                return false;
            }
        }

        return true;
    }

}