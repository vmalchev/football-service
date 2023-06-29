<?php


namespace Sportal\FootballApi\Application\KnockoutScheme\Output\Group;

use Sportal\FootballApi\Application\KnockoutScheme\Output;
use Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutGroupEntity;

class Mapper
{

    private Output\Team\Mapper $teamMapper;

    private Output\Match\Mapper $matchMapper;

    /**
     * Mapper constructor.
     * @param Output\Team\Mapper $teamMapper
     * @param Output\Match\Mapper $matchMapper
     */
    public function __construct(Output\Team\Mapper $teamMapper,
                                Output\Match\Mapper $matchMapper)
    {
        $this->teamMapper = $teamMapper;
        $this->matchMapper = $matchMapper;
    }

    public function map(IKnockoutGroupEntity $groupEntity): GroupDto
    {
        $teamDtos = [];
        $matchDtos = [];

        foreach ($groupEntity->getTeams() as $team) {
            $teamDtos[] = $this->teamMapper->map($team);
        }

        foreach ($groupEntity->getMatches() as $match) {
            $matchDtos[] = $this->matchMapper->map($match);
        }

        return new GroupDto($groupEntity->getId(), $groupEntity->getOrder(), $teamDtos, $matchDtos, $groupEntity->getChildObjectId());
    }
}