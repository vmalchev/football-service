<?php


namespace Sportal\FootballApi\Filter;


class StageFilter
{
    private ?array $tournamentIds = null;

    private ?array $seasonIds = null;

    private ?string $tournament_order = null;

    /**
     * @return array|null
     */
    public function getTournamentIds(): ?array
    {
        return $this->tournamentIds;
    }

    /**
     * @return array|null
     */
    public function getSeasonIds(): ?array
    {
        return $this->seasonIds;
    }

    /**
     * @return string|null
     */
    public function getTournamentOrder(): ?string
    {
        return $this->tournament_order;
    }

    /**
     * @param array|null $tournamentIds
     * @return StageFilter
     */
    public function setTournamentIds(?array $tournamentIds): StageFilter
    {
        $this->tournamentIds = $tournamentIds;
        return $this;
    }

    /**
     * @param array|null $seasonIds
     * @return StageFilter
     */
    public function setSeasonIds(?array $seasonIds): StageFilter
    {
        $this->seasonIds = $seasonIds;
        return $this;
    }

    /**
     * @param string|null $tournament_order
     * @return StageFilter
     */
    public function setTournamentOrder(?string $tournament_order): StageFilter
    {
        $this->tournament_order = $tournament_order;
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