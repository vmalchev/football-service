<?php
namespace Sportal\FootballApi\Asset;

interface AssetStorageInterface
{

    /**
     * Save an image binary string to a storage location
     * @param string $modelCass
     * @param string $fileName
     * @param string $imageBinary
     * @param string $type
     * @return boolean true on success, false otherwise
     */
    public function saveImage($modelCass, $fileName, $imageBinary, $type);
}