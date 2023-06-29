<?php
namespace Sportal\FootballApi\Domain;


interface IMappingRepository
{
    /**
     * @param $className
     * @param array $$enetpulseId
     * @return array mapping enetpulseId => ownId or enetpulseId => null if not present
     */
    public function getOwnId($entity, $enetpulseId): array;
}