<?php

namespace Sportal\FootballApi\Application\Round\Input;

use Sportal\FootballApi\Application\IDto;

class Dto implements IDto
{

    private ?string $seasonId;

    private ?string $stageId;

    /**
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