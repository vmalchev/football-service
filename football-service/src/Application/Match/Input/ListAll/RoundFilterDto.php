<?php

namespace Sportal\FootballApi\Application\Match\Input\ListAll;

class RoundFilterDto
{

    private string $stageId;

    private string $roundId;

    public function __construct(string $stageId, string $roundId)
    {
        $this->stageId = $stageId;
        $this->roundId = $roundId;
    }

    /**
     * @return string
     */
    public function getStageId(): string
    {
        return $this->stageId;
    }

    /**
     * @return string
     */
    public function getRoundId(): string
    {
        return $this->roundId;
    }

}