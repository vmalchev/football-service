<?php


namespace Sportal\FootballApi\Application\President\Input\Create;


use Sportal\FootballApi\Domain\President\IPresidentEntity;
use Sportal\FootballApi\Domain\President\IPresidentEntityFactory;

class Mapper
{
    private IPresidentEntityFactory $factory;

    /**
     * PresidentDtoToEntityMapper constructor.
     * @param IPresidentEntityFactory $factory
     */
    public function __construct(IPresidentEntityFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param Dto $inputDto
     * @return IPresidentEntity
     */
    public function map(Dto $inputDto): IPresidentEntity
    {
        return $this->factory->setEmpty()->setName($inputDto->getName())->create();
    }
}