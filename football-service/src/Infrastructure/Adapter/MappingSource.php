<?php


namespace Sportal\FootballApi\Infrastructure\Adapter;


use Sportal\FootballApi\Adapter\EntityType;
use Sportal\FootballApi\Adapter\IMappingSource;
use Sportal\FootballApi\Adapter\Provider;
use Sportal\FootballApi\Database\Database;

class MappingSource implements IMappingSource
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getMappingFromFeed(array $mappingRequests): array
    {
        if (!empty($mappingRequests)) {
            $query = $this->database->createQuery();
            $expr = $query->orX();
            $mappingGroups = [];
            foreach ($mappingRequests as $request) {
                $mappingGroups[$request->getEntityType()->getValue()][] = $request->getId();
            }
            foreach ($mappingGroups as $entityType => $entityIds) {
                $expr->add($query->andX()
                    ->eq('entity', $entityType)
                    ->in('enetpulse_id', array_unique($entityIds)));
            }

            $query->from('id_mapping_enetpulse')->where($expr);

            return $this->database->executeQuery($query, function ($row) {
                return new EntityMapping(Provider::ENETPULSE(), new EntityType($row['entity']), $row['entity_id'], $row['enetpulse_id']);
            });
        } else {
            return [];
        }
    }

    public function getMappingFromDomain(array $mappingRequests): array
    {
        //@TODO Implement in the future
        return [];
    }

}