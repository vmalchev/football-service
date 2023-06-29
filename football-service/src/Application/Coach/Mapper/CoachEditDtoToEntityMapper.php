<?php


namespace Sportal\FootballApi\Application\Coach\Mapper;


use Sportal\FootballApi\Application\Coach\Dto\CoachEditDto;
use Sportal\FootballApi\Domain\Coach\ICoachEntity;
use Sportal\FootballApi\Domain\Coach\ICoachEntityFactory;

class CoachEditDtoToEntityMapper
{
    private ICoachEntityFactory $factory;

    /**
     * @param ICoachEntityFactory $factory
     */
    public function __construct(ICoachEntityFactory $factory)
    {
        $this->factory = $factory;
    }

    public function map(CoachEditDto $coachEditDto): ICoachEntity
    {
        return $this->factory->setEmpty()
            ->setName($coachEditDto->getName())
            ->setCountryId($coachEditDto->getCountryId())
            ->setBirthdate($coachEditDto->getBirthdate())
            ->create();
    }
}
