<?php

namespace Sportal\FootballApi\Application\TournamentGroup\Output\ListAll;


class Mapper
{

    public function map(array $entities): CollectionDto
    {
        $dto = [];
        foreach ($entities as $entity) {
            $dto[] = new Dto(
                $entity->getCode(),
                $entity->getName(),
                $entity->getDescription()
            );
        }

        return new CollectionDto($dto);
    }

}