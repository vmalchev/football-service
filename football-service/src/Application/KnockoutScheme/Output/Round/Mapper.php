<?php


namespace Sportal\FootballApi\Application\KnockoutScheme\Output\Round;

use Sportal\FootballApi\Application\KnockoutScheme;
use Sportal\FootballApi\Domain\Group\IGroupEntity;
use Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutGroupEntity;
use Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutRoundEntity;

class Mapper
{

    private KnockoutScheme\Output\Group\Mapper $groupMapper;

    /**
     * Mapper constructor.
     * @param KnockoutScheme\Output\Group\Mapper $groupMapper
     */
    public function __construct(KnockoutScheme\Output\Group\Mapper $groupMapper)
    {
        $this->groupMapper = $groupMapper;
    }

    public function map(IKnockoutRoundEntity $roundEntity): RoundDto
    {
        return new RoundDto($roundEntity->getName(), $this->createGroupDtos($roundEntity->getGroups()));
    }

    /**
     * @param IKnockoutGroupEntity[] $groupEntities
     * @return KnockoutScheme\Output\Group\GroupDto[]
     */
    private function createGroupDtos(array $groupEntities): array
    {
        $groupDtos = [];

        foreach ($groupEntities as $group) {
            $groupDtos[] = $this->groupMapper->map($group);
        }

        return $groupDtos;
    }
}