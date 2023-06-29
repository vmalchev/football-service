<?php


namespace Sportal\FootballApi\Application\Venue\Mapper;


use Sportal\FootballApi\Application\Venue\Input;
use Sportal\FootballApi\Domain\Venue\IVenueEntity;
use Sportal\FootballApi\Domain\Venue\IVenueEntityFactory;

class VenueEditDtoToFactoryMapper
{
    private IVenueEntityFactory $factory;
    private Input\Update\Dto $inputDto;

    /**
     * VenueEditDtoToEntityMapper constructor.
     * @param IVenueEntityFactory $factory
     * @param Input\Update\Dto $inputDto
     */
    public function __construct(IVenueEntityFactory $factory,
                                Input\Update\Dto $inputDto)
    {
        $this->factory = $factory;
        $this->inputDto = $inputDto;
    }


    public function map(Input\Update\Dto $inputDto): IVenueEntity
    {
        $factory = $this->factory->setEmpty()
            ->setName($inputDto->getName())
            ->setCountryId($inputDto->getCountryId())
            ->setCityId($inputDto->getCityId());

        if (!is_null($inputDto->getProfile())) {
            $profileArr = array_filter($inputDto->getProfile()->jsonSerialize(), fn($value) => !is_null($value));
            if (!empty($profileArr)) {
                $factory->setProfile($profileArr);
            }
        }
        return $factory->create();
    }
}