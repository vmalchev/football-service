<?php


namespace Sportal\FootballApi\Application\StageTeam\Input\Update;


use Sportal\FootballApi\Domain\StageTeam\IStageTeamEntity;
use Sportal\FootballApi\Domain\StageTeam\IStageTeamEntityFactory;

class Mapper
{

    private IStageTeamEntityFactory $stageTeamEntityFactory;

    public function __construct(IStageTeamEntityFactory $stageTeamEntityFactory)
    {
        $this->stageTeamEntityFactory = $stageTeamEntityFactory;
    }

    /**
     * @param CollectionDto $collectionDto
     * @return IStageTeamEntity[]
     */
    public function map(CollectionDto $collectionDto): array
    {
        $teams = $collectionDto->getTeams();

        $stageTeamEntities = [];
        foreach ($teams as $team) {
            $stageTeamEntities[] = $this->stageTeamEntityFactory
                ->setStageId($collectionDto->getStageId())
                ->setTeamId($team->getTeamId())
                ->create();
        }

        return $stageTeamEntities;
    }

}