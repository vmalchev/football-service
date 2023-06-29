<?php
namespace Sportal\FootballApi\Asset;

/**
 *
 * Interface for objects that support building Asset URLs from mode's implementing AssetableInterface.
 */
interface AssetURLBuilderInterface
{

    /**
     * Generate a logo URL for the specified model class with a given filename.
     * @param string $modelClass The class of the model for which a logo URL is being generated.
     * @param string $filename The filename for the logo.
     * @return string Public URL where the logo can be fetched by clients.
     */
    public function getAssetUrl($modelClass, $filename, $type);
}