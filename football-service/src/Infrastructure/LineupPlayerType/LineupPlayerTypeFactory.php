<?php

namespace Sportal\FootballApi\Infrastructure\LineupPlayerType;


use Sportal\FootballApi\Domain\LineupPlayerType\ILineupPlayerTypeEntity;
use Sportal\FootballApi\Domain\LineupPlayerType\ILineupPlayerTypeFactory;

class LineupPlayerTypeFactory implements ILineupPlayerTypeFactory
{
    private ?string $id;
    private string $name;
    private string $category;
    private string $code;
    private int $sortOrder;

    public function setEntity(ILineupPlayerTypeEntity $lineupPlayerTypeEntity): ILineupPlayerTypeFactory
    {
        $factory = new LineupPlayerTypeFactory();

        $factory->id = $lineupPlayerTypeEntity->getId();
        $factory->name = $lineupPlayerTypeEntity->getName();
        $factory->category = $lineupPlayerTypeEntity->getCategory();
        $factory->code = $lineupPlayerTypeEntity->getCode();
        $this->sortOrder = $lineupPlayerTypeEntity->getSortOrder();

        return $this;
    }

    public function setEmpty(): ILineupPlayerTypeFactory
    {
        return new LineupPlayerTypeFactory();
    }

    public function create(): ILineupPlayerTypeEntity
    {
        return new LineupPlayerTypeEntity(
            $this->id, $this->name, $this->category, $this->code,
            $this->sortOrder
        );
    }

    public function setId(string $id): ILineupPlayerTypeFactory
    {
        $this->id = $id;
        return $this;
    }

    public function setName(string $name): ILineupPlayerTypeFactory
    {
        $this->name = $name;
        return $this;
    }

    public function setCategory(string $category): ILineupPlayerTypeFactory
    {
        $this->category = $category;
        return $this;
    }

    public function setCode(string $code): ILineupPlayerTypeFactory
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @param int $sortOrder
     * @return LineupPlayerTypeFactory
     */
    public function setSortOrder(int $sortOrder): LineupPlayerTypeFactory
    {
        $this->sortOrder = $sortOrder;
        return $this;
    }

}
