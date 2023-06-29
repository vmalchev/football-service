<?php
namespace Sportal\FootballApi\Adapter;


interface IMappingSource
{
    /**
     * @param MappingRequest[]
     * @return IEntityMapping[]
     */
    public function getMappingFromFeed(array $mappingRequests): array;

    /**
     * @param MappingRequest[]
     * @return IEntityMapping[]
     */
    public function getMappingFromDomain(array $mappingRequests): array;
}