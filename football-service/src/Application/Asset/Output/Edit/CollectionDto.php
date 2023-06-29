<?php


namespace Sportal\FootballApi\Application\Asset\Output\Edit;


use Sportal\FootballApi\Application\IDto;

/**
 * @SWG\Definition(definition="v2_AssetCollection")
 */
class CollectionDto implements \JsonSerializable, IDto
{
    /**
     * @var Dto[]
     */
    private array $assets;

    /**
     * @param Dto[] $assets
     */
    public function __construct(array $assets)
    {
        $this->assets = $assets;
    }

    /**
     * @return Dto[]
     */
    public function getAssetDtos(): array
    {
        return $this->assets;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}