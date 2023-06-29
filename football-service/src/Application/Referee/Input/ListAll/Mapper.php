<?php


namespace Sportal\FootballApi\Application\Referee\Input\ListAll;


use Sportal\FootballApi\Domain\Referee\RefereeFilter;
use Sportal\FootballApi\Domain\Referee\RefereeFilterFactory;

class Mapper
{
    private RefereeFilterFactory $factory;

    /**
     * Mapper constructor.
     * @param RefereeFilterFactory $factory
     */
    public function __construct(RefereeFilterFactory $factory)
    {
        $this->factory = $factory;
    }

    public function map(Dto $dto): RefereeFilter
    {
        return $this->factory->getFactory()
            ->setSeasonIds($dto->getSeasonIds())
            ->create();
    }

}