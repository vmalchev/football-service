<?php

namespace Sportal\FootballApi\Asset;

interface Assetable
{

    public function getId();

    public function getAssetFilename($type);

    public function setAssetFilename($type, $filename);

    public function generateAssetName($type);

    public function isSupported($type);

    public function hasAsset($type);

    /**
     * @return string Name of the asset model in storage
     */
    public function getAssetModelName();

    /**
     * @return string[] list of types of assets which are supported by the model
     */
    public function getAssetTypes();
}