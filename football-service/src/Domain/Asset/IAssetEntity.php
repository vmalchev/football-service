<?php

namespace Sportal\FootballApi\Domain\Asset;


interface IAssetEntity
{
    /**
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * @return AssetType
     */
    public function getType(): AssetType;

    /**
     * @return AssetEntityType
     */
    public function getEntity(): AssetEntityType;

    /**
     * @return string|null
     */
    public function getPath(): ?string;

    /**
     * @return string
     */
    public function getEntityId(): string;

    /**
     * @return AssetContextType|null
     */
    public function getContextType(): ?AssetContextType;

    /**
     * @return string|null
     */
    public function getContextId(): ?string;
}