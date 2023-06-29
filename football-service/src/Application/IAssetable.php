<?php


namespace Sportal\FootballApi\Application;


use Sportal\FootballApi\Domain\Asset\AssetEntityType;
use Sportal\FootballApi\Domain\Asset\AssetType;

interface IAssetable
{
    public function getEntityType(): AssetEntityType;

    public function setAssets(AssetType $assetType, array $assetUrl): void;

    public function getId(): ?string;
}