<?php

namespace Sportal\FootballApi\Domain\Stage;

class StageFilter
{
    private ?array $seasonIds = null;

    private ?array $tournamentIds = null;

    private function __construct()
    {
        // avoid instances
    }

    /**
     * @return array|null
     */
    public function getSeasonIds(): ?array
    {
        return $this->seasonIds;
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
     * @return array|null
     */
    public function getTournamentIds(): ?array
    {
        return $this->tournamentIds;
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

    public static function create(): StageFilter
    {
        return new StageFilter();
    }

}