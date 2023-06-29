<?php


namespace Sportal\FootballApi\Infrastructure\President;


use Sportal\FootballApi\Domain\President\IPresidentEntity;
use Sportal\FootballApi\Domain\President\IPresidentEntityFactory;

class PresidentEntityFactory implements IPresidentEntityFactory
{
    private ?string $id = null;
    private string $name;

    public function setEntity(IPresidentEntity $presidentEntity): IPresidentEntityFactory
    {
        $factory = new PresidentEntityFactory();

        $factory->id = $presidentEntity->getId();
        $factory->name = $presidentEntity->getName();

        return $factory;
    }

    public function setEmpty(): IPresidentEntityFactory
    {
        return new PresidentEntityFactory();
    }

    public function setId(string $id): IPresidentEntityFactory
    {
        $this->id = $id;
        return $this;
    }

    public function setName(string $name): IPresidentEntityFactory
    {
        $this->name = $name;
        return $this;
    }

    public function create(): IPresidentEntity
    {
        return new PresidentEntity($this->id, $this->name);
    }

    public function createFromArray(array $data): IPresidentEntity
    {
        $factory = new PresidentEntityFactory();

        $factory->id = $data[PresidentTable::FIELD_ID];
        $factory->name = $data[PresidentTable::FIELD_NAME];

        return $factory->create();
    }

}