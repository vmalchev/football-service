<?php

namespace Sportal\FootballApi\Application\Round\Output\ListAll;

use Sportal\FootballApi\Application\Round\Output\ICollectionDto;

class Mapper
{

    private \Sportal\FootballApi\Application\Round\Output\Profile\Mapper $singleRoundMapper;

    /**
     * @param \Sportal\FootballApi\Application\Round\Output\Profile\Mapper $singleRoundMapper
     */
    public function __construct(\Sportal\FootballApi\Application\Round\Output\Profile\Mapper $singleRoundMapper)
    {
        $this->singleRoundMapper = $singleRoundMapper;
    }


    public function map(array $roundEntities): ICollectionDto
    {
        $rounds = array();

        foreach ($roundEntities as $roundEntity) {
            $rounds[] = $this->singleRoundMapper->map($roundEntity);
        }

        return new CollectionDto($rounds);
    }

}