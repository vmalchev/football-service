<?php


namespace Sportal\FootballApi\Application\KnockoutScheme\Input;


use Sportal\FootballApi\Application\IDto;

class Dto implements IDto
{

    private string $stageId;

    /**
     * StageDto constructor.
     * @param string $stageId
     */
    public function __construct(string $stageId)
    {
        $this->stageId = $stageId;
    }

    /**
     * @return string
     */
    public function getStageId(): string
    {
        return $this->stageId;
    }
}