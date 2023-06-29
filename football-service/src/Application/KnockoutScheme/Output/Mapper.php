<?php


namespace Sportal\FootballApi\Application\KnockoutScheme\Output;


class Mapper
{

    private Scheme\Mapper $schemeMapper;

    /**
     * Mapper constructor.
     * @param Scheme\Mapper $schemeMapper
     */
    public function __construct(Scheme\Mapper $schemeMapper)
    {
        $this->schemeMapper = $schemeMapper;
    }


    public function map(array $schemeEntities): array
    {
        $schemeDtos = [];
        foreach ($schemeEntities as $schemeEntity) {
            $schemeDtos[] = $this->schemeMapper->map($schemeEntity);
        }

        return $schemeDtos;
    }
}