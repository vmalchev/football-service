<?php

namespace Sportal\FootballApi\Domain\Round;

class RoundFilter
{

    /**
     * @var string|null
     */
    private ?string $seasonId;

    /**
     * @var string|null
     */
    private ?string $stageId;

    /**
     * RoundFilter constructor.
     * @param string|null $seasonId
     * @param string|null $stageId
     */
    public function __construct(?string $seasonId, ?string $stageId)
    {
        $this->seasonId = $seasonId;
        $this->stageId = $stageId;
    }

    /**
     * @return string|null
     */
    public function getSeasonId(): ?string
    {
        return $this->seasonId;
    }

    /**
     * @return string|null
     */
    public function getStageId(): ?string
    {
        return $this->stageId;
    }

}