<?php
namespace Sportal\FootballApi\Infrastructure\Repository;

use Doctrine\DBAL\Connection;
use Illuminate\Support\Facades\DB;

class EnetpulseMappingRepository
{
    /**
     *
     * @var Connection
     */
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $entityType
     * @param int $enetpulseId
     * @return []
     */
    public function getMappingFromFeed($entityType, $entityId): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $stmt = $queryBuilder->select([
            'entity_id',
            "enetpulse_id as feed_id"
        ])
            ->from('id_mapping_enetpulse')
            ->where('entity = :entityType')
            ->andWhere('entity_id = :entity_id')
            ->setParameter('entityType', $entityType)
            ->setParameter('entity_id', $entityId)
            ->execute();

        $map = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $map[$row['entity_id']] = $row['feed_id'];
        }

        return $map;
    }
}