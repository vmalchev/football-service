<?php


namespace Sportal\FootballApi\Application\Stage\Output\Upsert;


use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\Stage\Output\Get\Dto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_SeasonStageCollection")
 */
class CollectionDto implements \JsonSerializable, IDto
{

    /**
     * @SWG\Property(property="stages")
     * @var Dto[]
     */
    private array $stages;

    public function __construct(array $stages)
    {
        $this->stages = $stages;
    }

    /**
     * @return array
     */
    public function getStages(): array
    {
        return $this->stages;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }

}