<?php


namespace Sportal\FootballApi\Adapter;


class MappingContainerBuilder
{
    private $domainMap = [];

    private $feedMap = [];

    public function withMapping(IEntityMapping $entityMapping, $entity)
    {
        $entityTypeValue = $entityMapping->getEntityType()->getValue();
        $providerValue = $entityMapping->getProvider()->getValue();
        $this->domainMap[$entityTypeValue][$entityMapping->getDomainId()] = $entity;
        $this->feedMap[$providerValue][$entityTypeValue][$entityMapping->getFeedId()] = $entity;
    }
    
    public function build()
    {
        return new MappingContainer($this->domainMap, $this->feedMap);
    }
}