<?php


namespace Sportal\FootballApi\Adapter;


class EntityAdapter
{
    private $sourceFactory;

    private $entityMappingCollectionFactory;

    private $builder;

    /**
     * @param $mappingSource
     * @param $sourceFactory
     */
    public function __construct(
        IEntitySourceFactory $sourceFactory,
        EntityMappingCollectionFactory $entityMappingCollectionFactory,
        MappingContainerBuilder $mappingContainerBuilder
    ) {
        $this->sourceFactory = $sourceFactory;
        $this->entityMappingCollectionFactory = $entityMappingCollectionFactory;
        $this->builder = $mappingContainerBuilder;
    }

    /**
     * @param MappingRequest[] $mappingRequests
     * @return MappingContainer
     */
    public function getMappingContainerFromFeed(array $mappingRequests): MappingContainer
    {
        $mappingCollection = $this->entityMappingCollectionFactory->createFromFeed($mappingRequests);
        foreach ($mappingCollection->getEntityTypes() as $entityType) {
            $entities = $this->sourceFactory
                ->getSource($entityType)
                ->findByIds($mappingCollection->getDomainIds($entityType));

            foreach ($entities as $entity) {
                $mappings = $mappingCollection->getMappings($entityType, $entity->getId());
                foreach ($mappings as $mapping) {
                    $this->builder->withMapping($mapping, $entity);
                }
            }
        }
        
        return $this->builder->build();
    }
}