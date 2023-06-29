<?php


namespace Sportal\FootballApi\Application\Referee\Mapper;


use Sportal\FootballApi\Application\Referee\Dto\RefereeEditDto;
use Sportal\FootballApi\Domain\Referee\IRefereeEntity;
use Sportal\FootballApi\Domain\Referee\IRefereeEntityFactory;

class RefereeEditDtoToEntityMapper
{
    private IRefereeEntityFactory $factory;

    /**
     * RefereeEditDtoToEntityMapper constructor.
     * @param IRefereeEntityFactory $factory
     */
    public function __construct(IRefereeEntityFactory $factory)
    {
        $this->factory = $factory;
    }

    public function map(RefereeEditDto $refereeEditDto): IRefereeEntity
    {
        return $this->factory->setEmpty()
            ->setName($refereeEditDto->getName())
            ->setCountryId($refereeEditDto->getCountryId())
            ->setBirthdate($refereeEditDto->getBirthdate())
            ->setActive($refereeEditDto->getActive())
            ->create();
    }
}