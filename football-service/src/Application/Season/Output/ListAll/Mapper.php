<?php


namespace Sportal\FootballApi\Application\Season\Output\ListAll;

use Sportal\FootballApi\Application\Season;

class Mapper
{
    private Season\Output\Get\Mapper $seasonMapper;

    /**
     * @param Season\Output\Get\Mapper $seasonMapper
     */
    public function __construct(Season\Output\Get\Mapper $seasonMapper)
    {
        $this->seasonMapper = $seasonMapper;
    }

    public function map(array $seasonEntities): Dto
    {
        if (empty($seasonEntities)) {
            return new Dto([]);
        }

        $seasonDtos = [];
        foreach ($seasonEntities as $seasonEntity) {
            $seasonDtos[] = $this->seasonMapper->map($seasonEntity);
        }

        return new Dto($seasonDtos);
    }
}