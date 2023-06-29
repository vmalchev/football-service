<?php


namespace Sportal\FootballApi\Infrastructure\KnockoutScheme;


use Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutGroupEntity;
use Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutRoundEntity;
use Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutRoundEntityFactory;

class KnockoutRoundEntityFactory implements \Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutRoundEntityFactory
{

    private string $name;

    private array $groups;

    /**
     * @inheritDoc
     */
    public function setName(string $name): IKnockoutRoundEntityFactory
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setGroups(array $groups): IKnockoutRoundEntityFactory
    {
        $this->groups = $groups;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setEmpty(): IKnockoutRoundEntityFactory
    {
        return new KnockoutRoundEntityFactory();
    }

    /**
     * @inheritDoc
     */
    public function setFrom(IKnockoutRoundEntity $roundEntity): IKnockoutRoundEntityFactory
    {
        $factory = new KnockoutRoundEntityFactory();
        $factory->setName($roundEntity->getName());
        $factory->setGroups($roundEntity->getGroups());

        return $factory;
    }

    /**
     * @inheritDoc
     */
    public function create(): IKnockoutRoundEntity
    {
        return new KnockoutRoundEntity($this->name, $this->groups);
    }
}