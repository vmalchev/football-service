<?php


namespace Sportal\FootballApi\Application\ProviderMappings\Dto\Output;


/**
 * Class Dto
 * @package Sportal\FootballApi\Application\ProviderMappings\Dto\Output
 *
 * @SWG\Definition(definition="v2_MappingsDto")
 */
class CollectionDto implements \JsonSerializable
{

    /**
     * @var MappingDto[]
     * @SWG\Property(property="mappings")
     */
    private $mappings;

    /**
     * Dto constructor.
     * @param MappingDto[] $mappings
     */
    public function __construct(array $mappings)
    {
        $this->mappings = $mappings;
    }

    /**
     * @return MappingDto[]
     */
    public function getMappings(): array
    {
        return $this->mappings;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}