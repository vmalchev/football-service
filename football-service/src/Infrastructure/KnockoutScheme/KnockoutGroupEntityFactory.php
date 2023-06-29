<?php


namespace Sportal\FootballApi\Infrastructure\KnockoutScheme;


use Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutGroupEntity;
use Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutGroupEntityFactory;

class KnockoutGroupEntityFactory implements IKnockoutGroupEntityFactory
{

    private string $id;

    private int $order;

    private array $teams;

    private array $matches;

    private ?string $childObjectId;

    /**
     * @inheritDoc
     */
    public function setId(string $id): IKnockoutGroupEntityFactory
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setOrder(int $order): IKnockoutGroupEntityFactory
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setTeams(array $teams): IKnockoutGroupEntityFactory
    {
        $this->teams = $teams;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setMatches(array $matches): IKnockoutGroupEntityFactory
    {
        $this->matches = $matches;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setChildObjectId(?string $childObjectId): IKnockoutGroupEntityFactory
    {
        $this->childObjectId = $childObjectId;
        return $this;
    }

    public function setEmpty(): IKnockoutGroupEntityFactory
    {
        return new KnockoutGroupEntityFactory();
    }

    public function setFrom(IKnockoutGroupEntity $groupEntity): IKnockoutGroupEntityFactory
    {
        $factory = new KnockoutGroupEntityFactory();
        $factory->setId($groupEntity->getId());
        $factory->setOrder($groupEntity->getOrder());
        $factory->setTeams($groupEntity->getTeams());
        $factory->setMatches($groupEntity->getMatches());
        $factory->setChildObjectId($groupEntity->getChildObjectId());

        return $factory;
    }

    public function create(): IKnockoutGroupEntity
    {
        return new KnockoutGroupEntity($this->id, $this->order,
            $this->teams, $this->matches, $this->childObjectId);
    }

}