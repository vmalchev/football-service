<?php


namespace Sportal\FootballApi\Adapter;


class EntityMappingCollection
{
    /**
     * @var IEntityMapping[]
     */
    private $collection;

    public function __construct(array $entityMappings)
    {
        $this->collection = $entityMappings;
    }

    /**
     * @param EntityType $entityType
     * @return array
     */
    public function getDomainIds(EntityType $entityType): array
    {
        $filteredDomainIds = [];

        foreach ($this->collection as $entityMapping) {
            if ($entityMapping->getEntityType() == $entityType) {
                $filteredDomainIds[] = $entityMapping->getDomainId();
            }
        }

        return $filteredDomainIds;
    }

    /**
     * @return array
     */
    public function getEntityTypes(): array
    {
        $entityTypes = [];
        foreach ($this->collection as $entityMapping) {
            $entityTypes[$entityMapping->getEntityType()->getValue()] = $entityMapping->getEntityType();
        }

        return array_values($entityTypes);
    }

    /**
     * @param EntityType $entityType
     * @param $domainId
     * @return IEntityMapping[]
     */
    public function getMappings(EntityType $entityType, $domainId)
    {
        $mappings = [];
        foreach ($this->collection as $mapping) {
            if ($mapping->getEntityType()->getValue() == $entityType->getValue() && $mapping->getDomainId() == $domainId) {
                $mappings[] = $mapping;
            }
        }
        return $mappings;
    }

    public function getDomainId(EntityType $type, string $feedId): ?string {
        foreach ($this->collection as $mapping) {
            if ($mapping->getEntityType()->equals($type) && $feedId == $mapping->getFeedId()) {
                return $mapping->getDomainId();
            }
        }
        return null;
    }
}