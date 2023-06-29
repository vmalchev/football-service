<?php


namespace Sportal\FootballApi\Adapter;

use Sportal\FootballApi\Infrastructure\Adapter\EntityMapping;

class EntityMappingCollectionFactory
{
    private $mappingSource;

    public function __construct(IMappingSource $mappingSource)
    {
        $this->mappingSource = $mappingSource;
    }

    /**
     * @param MappingRequest[] $entityMappings
     * @return EntityMappingCollection
     */
    public function createFromFeed(array $mappingRequests): EntityMappingCollection
    {
        $entityMappings = $this->mappingSource->getMappingFromFeed($mappingRequests);
        return new EntityMappingCollection($entityMappings);
    }
}