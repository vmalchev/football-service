<?php

namespace Sportal\FootballApi\Application\Asset\Input\Edit;

use Sportal\FootballApi\Application\IDto;

class CollectionDto implements IDto
{
    /**
     * @var Dto[]
     */
    private array $assetDtos;

    /**
     * @param Dto[] $assetDtos
     */
    public function __construct(array $assetDtos)
    {
        $this->assetDtos = $assetDtos;
    }

    /**
     * @return Dto[]
     */
    public function getAssetDtos(): array
    {
        return $this->assetDtos;
    }
}