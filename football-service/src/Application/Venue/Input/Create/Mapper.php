<?php


namespace Sportal\FootballApi\Application\Venue\Input\Create;


use Sportal\FootballApi\Application\Venue\Input;
use Sportal\FootballApi\Domain\Venue\IVenueEntity;
use Sportal\FootballApi\Domain\Venue\IVenueEntityFactory;

class Mapper
{
    private IVenueEntityFactory $factory;
    private Input\Create\Dto $inputDto;

    /**
     * VenueEditDtoToEntityMapper constructor.
     * @param IVenueEntityFactory $factory
     * @param Input\Create\Dto $inputDto
     */
    public function __construct(IVenueEntityFactory $factory,
                                Input\Create\Dto $inputDto)
    {
        $this->factory = $factory;
        $this->inputDto = $inputDto;
    }


    public function map(Input\Create\Dto $inputDto): IVenueEntity
    {
        if (is_null($inputDto)) {
            return null;
        }

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