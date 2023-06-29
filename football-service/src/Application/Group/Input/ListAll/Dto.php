<?php


namespace Sportal\FootballApi\Application\Group\Input\ListAll;


use Sportal\FootballApi\Application\IDto;

class Dto implements IDto
{

    private string $stageId;

    public function __construct(string $stageId)
    {
        $this->stageId = $stageId;
    }

    public function getStageId(): string
    {
        return $this->stageId;
    }

}