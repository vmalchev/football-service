<?php

namespace Sportal\FootballApi\Application\Round\Output\ListAll;

use Sportal\FootballApi\Application\Round\Output\Profile\Dto;
use Sportal\FootballApi\Application\Round\Output\ICollectionDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_RoundTypes")
 */
class CollectionDto implements ICollectionDto, \JsonSerializable
{

    /**
     * @SWG\Property(property="rounds")
     * @var Dto[]
     */
    private array $rounds;

    /**
     * @param Dto[] $rounds
     */
    public function __construct(array $rounds)
    {
        $this->rounds = $rounds;
    }

    /**
     * @return Dto[]
     */
    public function getRounds(): array
    {
        return $this->rounds;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}