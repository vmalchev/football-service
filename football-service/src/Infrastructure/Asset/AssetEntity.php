<?php


namespace Sportal\FootballApi\Infrastructure\Asset;


use Sportal\FootballApi\Domain\Asset\AssetContextType;
use Sportal\FootballApi\Domain\Asset\AssetEntityType;
use Sportal\FootballApi\Domain\Asset\AssetType;
use Sportal\FootballApi\Domain\Asset\IAssetEntity;
use Sportal\FootballApi\Infrastructure\Database\GeneratedIdDatabaseEntity;

final class AssetEntity extends GeneratedIdDatabaseEntity implements IAssetEntity
{
    /**
     * @var string
     */
    private ?string $id;

    /**
     * @var AssetType
     */
    private AssetType $type;

    /**
     * @var AssetEntityType
     */
    private AssetEntityType $entity;

    /**
     * @var string
     */
    private string $entityId;

    /**
     * @var string|null
     */
    private ?string $path;

    /**
     * @var AssetContextType|null
     */
    private ?AssetContextType $contextType;

    /**
     * @var string|null
     */
    private ?string $contextId;

    /**
     * AssetEntity constructor.
     * @param string|null $id
     * @param AssetType $type
     * @param AssetEntityType $entity
     * @param string $entityId
     * @param string $path
     * @param AssetContextType|null $contextType
     * @param string|null $contextId
     */
    public function __construct(?string $id,
                                AssetType $type,
                                AssetEntityType $entity,
                                string $entityId,
                                ?string $path,
                                ?AssetContextType $contextType,
                                ?string $contextId)
    {
        $this->id = $id;
        $this->type = $type;
        $this->entity = $entity;
        $this->entityId = $entityId;
        $this->path = $path;
        $this->contextType = $contextType;
        $this->contextId = $contextId;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return AssetType
     */
    public function getType(): AssetType
    {
        return $this->type;
    }

    /**
     * @return AssetEntityType
     */
    public function getEntity(): AssetEntityType
    {
        return $this->entity;
    }

    /**
     * @return string
     */
    public function getEntityId(): string
    {
        return $this->entityId;
    }

    /**
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @return AssetContextType|null
     */
    public function getContextType(): ?AssetContextType
    {
        return $this->contextType;
    }

    /**
     * @return string|null
     */
    public function getContextId(): ?string
    {
        return $this->contextId;
    }

    public function withId(string $id): GeneratedIdDatabaseEntity
    {
        $this->id = $id;
        return $this;
    }

    public function getDatabaseEntry(): array
    {
        return [
            AssetTable::FIELD_ID => $this->getId(),
            AssetTable::FIELD_ENTITY => $this->getEntity()->getValue(),
            AssetTable::FIELD_ENTITY_ID => $this->getEntityId(),
            AssetTable::FIELD_TYPE => $this->getType()->getValue(),
            AssetTable::FIELD_PATH => $this->getPath(),
            AssetTable::FIELD_CONTEXT_TYPE => !is_null($this->getContextType()) ? $this->getContextType()->getValue()
                : null,
            AssetTable::FIELD_CONTEXT_ID => $this->getContextId(),
            AssetTable::FIELD_UPDATED_AT => (new \DateTime())->format(\DateTime::ATOM),
        ];
    }
}