<?php

namespace Sportal\FootballApi\Application\Round\Input;

use Sportal\FootballApi\Domain\Round\RoundFilter;
use Sportal\FootballApi\Domain\Round\RoundFilterFactory;

class Mapper
{

    private RoundFilterFactory $factory;

    /**
     * Mapper constructor.
     * @param RoundFilterFactory $factory
     */
    public function __construct(RoundFilterFactory $factory)
    {
        $this->factory = $factory;
    }

    public function map(Dto $dto): RoundFilter
    {
        return $this->factory->getFactory()
            ->setSeasonId($dto->getSeasonId())
            ->setStageId($dto->getStageId())
            ->create();
    }
}