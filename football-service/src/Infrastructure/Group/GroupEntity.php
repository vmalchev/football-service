<?php


namespace Sportal\FootballApi\Infrastructure\Group;


use Sportal\FootballApi\Domain\Group\IGroupEntity;
use Sportal\FootballApi\Infrastructure\Database\GeneratedIdDatabaseEntity;
use Sportal\FootballApi\Infrastructure\Database\IDatabaseEntity;

class GroupEntity extends GeneratedIdDatabaseEntity implements IGroupEntity, IDatabaseEntity
{
    private ?string $id;
    private string $name;
    private string $stageId;
    private ?int $sortorder;

    /**
     * GroupEntity constructor.
     * @param string|null $id
     * @param string $name
     * @param string $stageId
     * @param int|null $sortorder
     */
    public function __construct(?string $id, string $name, string $stageId, ?int $sortorder)
    {
        $this->id = $id;
        $this->name = $name;
        $this->stageId = $stageId;
        $this->sortorder = $sortorder;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getStageId(): string
    {
        return $this->stageId;
    }

    /**
     * @return int|null
     */
    public function getSortorder(): ?int
    {
        return $this->sortorder;
    }


    public function withId(string $id): GeneratedIdDatabaseEntity
    {
        $entity = clone $this;
        $entity->id = $id;
        return $entity;
    }

    public function getDatabaseEntry(): array
    {
        return [
            GroupTableMapper::FIELD_NAME => $this->name,
            GroupTableMapper::FIELD_STAGE_ID => $this->stageId,
            GroupTableMapper::FIELD_SORT_ORDER => $this->sortorder,
            GroupTableMapper::FIELD_UPDATED_AT => (new \DateTime())->format(\DateTimeInterface::ATOM)
        ];
    }
}