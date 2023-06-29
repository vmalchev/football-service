<?php

namespace Sportal\FootballApi\Domain\Round;

class RoundFilterFactory
{

    private ?string $seasonId = null;

    private ?string $stageId = null;

    public function getFactory(): RoundFilterFactory
    {
        return new RoundFilterFactory();
    }

    /**
     * @param string|null $seasonId
     * @return RoundFilterFactory
     */
    public function setSeasonId(?string $seasonId): RoundFilterFactory
    {
        $this->seasonId = $seasonId;
        return $this;
    }

    /**
     * @param string|null $stageId
     * @return RoundFilterFactory
     */
    public function setStageId(?string $stageId): RoundFilterFactory
    {
        $this->stageId = $stageId;
        return $this;
    }

    public function create(): RoundFilter
    {
        return new RoundFilter($this->seasonId, $this->stageId);
    }
}