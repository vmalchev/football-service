<?php


namespace Sportal\FootballApi\Application\Player\Output\Get\Teams;

use Sportal\FootballApi\Domain\TeamSquad\ITeamPlayerEntity;

class Mapper
{

    private \Sportal\FootballApi\Application\Team\Output\Get\Mapper $teamMapper;

    /**
     * Mapper constructor.
     * @param \Sportal\FootballApi\Application\Team\Output\Get\Mapper $teamMapper
     */
    public function __construct(\Sportal\FootballApi\Application\Team\Output\Get\Mapper $teamMapper)
    {
        $this->teamMapper = $teamMapper;
    }

    /**
     * @param ITeamPlayerEntity[] $teamPlayerEntities
     * @return Dto[]
     */
    public function map(array $teamPlayerEntities): array
    {
        $result = [];
        foreach ($teamPlayerEntities as $teamPlayerEntity) {
            $teamDto = $this->teamMapper->map($teamPlayerEntity->getTeam());

            $result[] = new Dto(
                $teamDto,
                $teamPlayerEntity->getContractType(),
                is_null($teamPlayerEntity->getStartDate()) ? null : $teamPlayerEntity->getStartDate()->format("Y-m-d"),
                $teamPlayerEntity->getShirtNumber(),
                $teamPlayerEntity->getStatus()
            );
        }

        return $result;
    }
}