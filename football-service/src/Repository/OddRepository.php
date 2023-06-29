<?php
namespace Sportal\FootballApi\Repository;

use Alexsabdev\Odds\DecimalOdd;
use Doctrine\DBAL\Connection;
use Sportal\FootballApi\Cache\CacheManager;
use Sportal\FootballApi\Infrastructure\Entity\ApplicationLinkEntity;
use Sportal\FootballApi\Model\Enum\OddFormat;
use Sportal\FootballApi\Model\EventStatus;
use Sportal\FootballApi\Model\Odd;
use Sportal\FootballApi\Model\OddProvider;

class OddRepository extends Repository
{

    protected $oddProviderRepository;

    protected $oddLinkRepository;

    public function __construct(Connection $conn, CacheManager $cacheManager,
        OddProviderRepository $oddProviderRepository, OddLinkRepository $oddLinkRepository)
    {
        parent::__construct($conn, $cacheManager);
        $this->oddProviderRepository = $oddProviderRepository;
        $this->oddLinkRepository = $oddLinkRepository;
    }

    public function createObject(array $oddArr)
    {
        $odd = new Odd();
        $odd->setEventId($oddArr['event_id'])
            ->setOddProvider($oddArr['odd_provider'])
            ->setSource($oddArr['source']);
        if (isset($oddArr['reference'])) {
            $odd->setReference($oddArr['reference']);
        }
        $odd->setData(is_array($oddArr['data']) ? $oddArr['data'] : json_decode($oddArr['data'], true));
        return $odd;
    }

    public function buildObject(array $oddArr)
    {
        $oddArr['odd_provider'] = $this->oddProviderRepository->buildObject($oddArr['odd_provider']);
        return $this->createObject($oddArr);
    }

    /**
     * @param array $oddProviderIds
     * @param array $eventIds
     * @param null $oddFormat
     * @return Odd[]
     */
    public function findByKeys(array $oddProviderIds, array $eventIds, $oddFormat=null) {
        $qb = $this->conn->createQueryBuilder();
        $select = [
            'o.event_id as event_id',
            'o.source as source',
            "o.data as data"
        ];

        $oddProviderColumns = $this->oddProviderRepository->getColumns();

        foreach ($oddProviderColumns as $opColumns) {
            $select[] = "op." . $opColumns . " as op_" . $opColumns;
        }

        $qb->select($select);

        $qb->from('odd', 'o')
            ->innerJoin('o', 'odd_provider', 'op', 'o.odd_provider_id = op.id')
            ->innerJoin('o', 'event', 'e', 'e.id = o.event_id')
            ->innerJoin('e', 'event_status', 'es', 'e.event_status_id = es.id');

        $where = $qb->expr()->andX();
        $where->add('es.type = ' . $qb->createPositionalParameter(EventStatus::TYPE_NOTSTARTED));
        $where->add($qb->expr()->in('o.event_id', $eventIds));
        $where->add($qb->expr()->in('o.odd_provider_id', $oddProviderIds));
        $qb->where($where);

        $stmt = $qb->execute();

        $data = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            if (empty($row['data'])) {
                continue;
            }
            $oddProviderArr = [];
            foreach ($oddProviderColumns as $opColumns) {
                $oddProviderArr[$opColumns] = $row['op_' . $opColumns];
            }

            $oddsData = json_decode($row['data'], true);

            if ($oddFormat->getValue() !== OddFormat::DECIMAL()->getValue()) {
                foreach ($oddsData as &$oddData) {
                    foreach ($oddData as &$odd) {
                        $oddsConverter = new DecimalOdd($odd['odds']);
                        if ($oddFormat->getValue() === OddFormat::FRACTIONAL()->getValue()) {
                            $odd['odds'] = $oddsConverter->toFractional()->value();
                        } elseif ($oddFormat->getValue() === OddFormat::MONEYLINE()->getValue()) {
                            $odd['odds'] = $oddsConverter->toMoneyline()->value();
                        }
                    }
                }
            }

             $data[] = $this->createObject(
                [
                    'event_id' => $row['event_id'],
                    'source' => $row['source'],
                    'odd_provider' => OddProvider::create($oddProviderArr),
                    'data' => $oddsData
                ]);
        }
        return $data;
    }

    public function findPreGameOdds(\DateTime $eventAfter, \DateTime $eventTo, $clientCode = null)
    {
        $qb = $this->conn->createQueryBuilder();
        
        $select = [
            'o.event_id as event_id',
            'o.source as source',
            "o.data as data"
        ];
        
        $oddProviderColumns = $this->oddProviderRepository->getColumns();
        
        foreach ($oddProviderColumns as $opColumns) {
            $select[] = "op." . $opColumns . " as op_" . $opColumns;
        }
        
        $qb->select($select);
        
        $qb->from('odd', 'o')
            ->innerJoin('o', 'odd_provider', 'op', 'o.odd_provider_id = op.id')
            ->innerJoin('o', 'event', 'e', 'e.id = o.event_id')
            ->innerJoin('e', 'event_status', 'es', 'e.event_status_id = es.id');
        
        $where = $qb->expr()->andX();
        $where->add('e.start_time >= ' . $qb->createPositionalParameter(static::formatTime($eventAfter)));
        $where->add('e.start_time <= ' . $qb->createPositionalParameter(static::formatTime($eventTo)));
        $where->add('es.type = ' . $qb->createPositionalParameter(EventStatus::TYPE_NOTSTARTED));
        
        $qb->where($where);
        $qb->addOrderBy('e.start_time');
        
        if ($clientCode !== null) {
            $qb->addSelect('ol.oddslip_link as oddslip_link')->addSelect('ol.fallback_link as fallback_link');
            $qb->innerJoin('op', 'odd_link', 'ol', 'ol.odd_provider_id = op.id');
            $qb->innerJoin('ol', 'odd_client', 'oc', 'ol.odd_client_id = oc.id');
            $where->add('oc.code = ' . $qb->createPositionalParameter($clientCode));
            $qb->addOrderBy('ol.sortorder');
        }
        
        $stmt = $qb->execute();
        $data = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            if (empty($row['data'])) {
                continue;
            }
            $oddProviderArr = [];
            foreach ($oddProviderColumns as $opColumns) {
                $oddProviderArr[$opColumns] = $row['op_' . $opColumns];
            }
            $provider = $this->oddProviderRepository->createObject($oddProviderArr);
            $obj = $this->createObject(
                [
                    'event_id' => $row['event_id'],
                    'source' => $row['source'],
                    'odd_provider' => $provider,
                    'data' => $row['data']
                ]);
            if (isset($row['oddslip_link']) && isset($row['fallback_link'])) {
                $obj->setLinks([
                    new ApplicationLinkEntity('default', $row['oddslip_link'], $row['fallback_link'])
                ]);
            }
            $eventId = $row['event_id'];
            if (! isset($data[$row['event_id']])) {
                $data[$row['event_id']] = [
                    'event_id' => $row['event_id'],
                    'odds' => []
                ];
            }
            $data[$eventId]['odds'][] = $obj;
        }
        return array_values($data);
    }

    /**
     *
     * @param integer $id id of the event
     * @param string $source name of the source
     * @return \Sportal\FootballApi\Model\Odd
     */
    public function findByEvent($id, $source = null, $oddProviderId = null)
    {
        $filter = [
            'event_id' => $id
        ];
        if ($source !== null) {
            $filter['source'] = $source;
        }
        
        if ($oddProviderId !== null) {
            $filter['odd_provider_id'] = $oddProviderId;
        }
        
        return $this->findAll($filter);
    }

    public function findByEventClient($id, $clientCode, $oddProviderId = null)
    {
        $odds = $this->findByEvent($id, null, $oddProviderId);
        if (! empty($clientCode)) {
            $links = $this->oddLinkRepository->findByClient($clientCode);
            $linkedOdds = [];

            foreach ($links as $oddLink) {
                foreach ($odds as $odd) {
                    if ($odd->getOddProvider()->getId() == $oddLink->getOddProviderId()) {

                        $odd->setLinks($oddLink->getLinks());
                        $linkedOdds[] = $odd;
                    }
                }
            }
            return $linkedOdds;
        }
        return $odds;
    }

    public function find($id)
    {
        return $this->getByPk($this->getModelClass(), $id, [
            $this,
            'buildObject'
        ], $this->getJoin());
    }

    public function findAll($filter = [])
    {
        return $this->queryPersistance($filter, [
            $this,
            'buildObject'
        ], $this->getJoin());
    }

    public function getModelClass()
    {
        return Odd::class;
    }

    public function refreshEventOdds(array $changedKeys, array $createdModels, $eventId)
    {
        if (! empty($createdModels)) {
            $this->refreshReleventLists([
                'event_id' => $eventId
            ]);
        }
    }

    public function findByEventIndex($eventId, $sourceName)
    {
        $data = [];
        $odds = $this->findByEvent($eventId, $sourceName);
        foreach ($odds as $odd) {
            $data[static::getKeyName($odd)] = $odd;
        }
        return $data;
    }

    public function flush(array $created, array $updated, array $deleted)
    {
        $table = $this->getPersistanceName($this->getModelClass());
        
        $this->conn->transactional(
            function () use ($table, $created, $updated, $deleted) {
                foreach ($created as $model) {
                    $this->conn->insert($table, $model->getPersistanceMap());
                }
                foreach ($updated as $model) {
                    $this->conn->update($table, $model->getPersistanceMap(), $model->getPrimaryKeyMap());
                }
                foreach ($deleted as $model) {
                    $this->conn->delete($table, $model->getPrimaryKeyMap());
                }
            });
        
        $models = array_merge($updated, $created);
        if (! empty($models)) {
            $this->cacheManager->setAll($table, $models);
        }
        foreach ($deleted as $delete) {
            $this->cacheManager->delInstance($table, $delete->getPrimaryKeyMap());
        }
    }

    public function getJoin()
    {
        static $join = null;
        if ($join === null) {
            $join = [
                [
                    'className' => $this->oddProviderRepository->getModelClass(),
                    'type' => 'inner',
                    'columns' => $this->oddProviderRepository->getColumns(),
                    'join' => $this->oddProviderRepository->getJoin()
                ]
            ];
        }
        return $join;
    }

    public static function getKeyName(Odd $model)
    {
        return CacheManager::getPersistanceId($model->getPrimaryKeyMap());
    }

    protected function getPrimaryKeys()
    {
        return Odd::PRIMARY_KEYS;
    }
}