<?php


namespace Sportal\FootballApi\Application\StageTeam\Input\Update;


use Sportal\FootballApi\Application\IDto;

class CollectionDto implements IDto
{

    /**
     * @var Dto[]
     */
    private array $teams;

    private string $stageId;

    public function __construct(array $teams)
    {
        $this->teams = $teams;
    }

    /**
     * @return Dto[]
     */
    public function getTeams(): array
    {
        return $this->teams;
    }

    /**
     * @return string
     */
    public function getStageId(): string
    {
        return $this->stageId;
    }

    /**
     * @param string $stageId
     */
    public function setStageId(string $stageId): void
    {
        $this->stageId = $stageId;
    }

}