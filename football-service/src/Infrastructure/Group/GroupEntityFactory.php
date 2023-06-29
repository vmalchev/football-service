<?php


namespace Sportal\FootballApi\Infrastructure\Group;


use Sportal\FootballApi\Domain\Group\IGroupEntity;
use Sportal\FootballApi\Domain\Group\IGroupEntityFactory;

class GroupEntityFactory implements IGroupEntityFactory
{
    private ?string $id = null;
    private string $name;
    private string $stageId;
    private ?int $sortorder = null;

    /**
     * @param string|null $id
     * @return GroupEntityFactory
     */
    public function setId(?string $id): GroupEntityFactory
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $name
     * @return GroupEntityFactory
     */
    public function setName(string $name): GroupEntityFactory
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $stageId
     * @return GroupEntityFactory
     */
    public function setStageId(string $stageId): GroupEntityFactory
    {
        $this->stageId = $stageId;
        return $this;
    }


    public function setEmpty(): IGroupEntityFactory
    {
        return new GroupEntityFactory();
    }

    public function create(): IGroupEntity
    {
        return new GroupEntity(
            $this->id,
            $this->name,
            $this->stageId,
            $this->sortorder
        );
    }

    /**
     * @param int|null $sortorder
     * @return GroupEntityFactory
     */
    public function setSortorder(?int $sortorder): GroupEntityFactory
    {
        $this->sortorder = $sortorder;
        return $this;
    }
}