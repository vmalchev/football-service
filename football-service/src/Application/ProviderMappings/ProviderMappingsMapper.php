<?php


namespace Sportal\FootballApi\Application\ProviderMappings;


use Sportal\FootballApi\Adapter\EntityMappingCollectionFactory;
use Sportal\FootballApi\Adapter\EntityType;
use Sportal\FootballApi\Adapter\MappingRequest;
use Sportal\FootballApi\Adapter\Provider;
use Sportal\FootballApi\Application\ProviderMappings\Dto\Input\Dto;
use Sportal\FootballApi\Application\ProviderMappings\Dto\Output\CollectionDto;
use Sportal\FootballApi\Application\ProviderMappings\Dto\Output\MappingDto;

class ProviderMappingsMapper
{
    private EntityMappingCollectionFactory $mappingCollectionFactory;

    /**
     * ProviderMappingsMapper constructor.
     * @param EntityMappingCollectionFactory $mappingCollectionFactory
     */
    public function __construct(EntityMappingCollectionFactory $mappingCollectionFactory)
    {
        $this->mappingCollectionFactory = $mappingCollectionFactory;
    }

    public function map(Dto $input): CollectionDto
    {
        $provider = $input->getProvider();
        $mappingRequests = array();

        foreach ($input->getMappingRequests() as $mappingRequest) {
            $mappingRequests[] = new MappingRequest(new Provider($provider),
                EntityType::from(EntityType::toArray()[$mappingRequest->getEntityType()]), $mappingRequest->getProviderId());
        }

        $mappingCollection = $this->mappingCollectionFactory->createFromFeed($mappingRequests);

        $mappings = array();
        foreach ($input->getMappingRequests() as $mappingRequest) {
            $mappings[] = new MappingDto(
                strtoupper($provider),
                $mappingRequest->getEntityType(),
                $mappingCollection->getDomainId(EntityType::from(EntityType::toArray()[$mappingRequest->getEntityType()]), $mappingRequest->getProviderId()),
                $mappingRequest->getProviderId()
            );
        }

        return new CollectionDto($mappings);
    }
}