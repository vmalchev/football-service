<?php
namespace Sportal\FootballApi\Adapter;

class MappingContainer
{

    /**
     * @var array
     */
    private $feedMap;

    /**
     * @var array
     */
    private $domainMap;

    public function __construct(array $domainMap, array $feedMap)
    {
        $this->domainMap = $domainMap;
        $this->feedMap = $feedMap;
    }

    public function getByFeedId(Provider $provider, EntityType $type, $feedId)
    {
        if (isset($this->feedMap[$provider->getValue()][$type->getValue()][$feedId])) {
            return $this->feedMap[$provider->getValue()][$type->getValue()][$feedId];
        }

        return null;
    }

    public function getByDomainId(EntityType $type, $domainId)
    {
        if (isset($this->domainMap[$type->getValue()][$domainId])) {
            return $this->domainMap[$type->getValue()][$domainId];
        }

        return null;
    }

    public function getAllEntities()
    {
        $allEntities = [];
        
        foreach ($this->domainMap as $entityTypeIdMap) {
            $allEntities = array_merge($allEntities, array_values($entityTypeIdMap));
        }
        
        return $allEntities;
    }
}