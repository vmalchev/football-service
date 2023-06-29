<?php


namespace Sportal\FootballApi\Application\City\Mapper;


use Sportal\FootballApi\Application\City\Dto\CityEditDto;
use Sportal\FootballApi\Domain\City\ICityEntity;
use Sportal\FootballApi\Domain\City\ICityEntityFactory;

class CityEditDtoToEntityMapper
{
    private ICityEntityFactory $factory;

    /**
     * CityEditDtoToEntityMapper constructor.
     * @param ICityEntityFactory $factory
     */
    public function __construct(ICityEntityFactory $factory)
    {
        $this->factory = $factory;
    }

    public function map(CityEditDto $cityEditDto): ICityEntity
    {
        return $this->factory->setEmpty()
            ->setName($cityEditDto->getName())
            ->setCountryId($cityEditDto->getCountryId())
            ->create();
    }
}