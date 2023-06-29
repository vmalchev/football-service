<?php
namespace Sportal\FootballApi\Repository;

use Doctrine\DBAL\Connection;
use Sportal\FootballApi\Cache\CacheManager;
use Sportal\FootballApi\Model\OddLink;
use Sportal\FootballApi\Model\OddProvider;
use Sportal\FootballApi\Infrastructure\Entity\ApplicationLinkEntity;

class OddLinkRepository extends Repository
{

    private $clientRepository;

    public function __construct(Connection $conn, CacheManager $cacheManager, OddClientRepository $clientRepository)
    {
        parent::__construct($conn, $cacheManager);
        $this->clientRepository = $clientRepository;
    }

    public function createObject(array $oddLinkArr)
    {
        $oddLink = new OddLink();
        $oddLink->setOddClient($oddLinkArr['odd_client'])
            ->setOddProviderId($oddLinkArr['odd_provider_id'])
            ->setOddProvider(OddProvider::create($oddLinkArr['odd_provider']))
            ->setFallbackLink($oddLinkArr['fallback_link'])
            ->setOddslipLink($oddLinkArr['oddslip_link']);
        if (isset($oddLinkArr['sortorder'])) {
            $oddLink->setSortorder($oddLinkArr['sortorder']);
        }
        if (! empty($oddLinkArr['links'])) {
            $links = array_map([
                ApplicationLinkEntity::class,
                'create'
            ], json_decode($oddLinkArr['links'], true));
            $oddLink->setLinks($links);
        }
        return $oddLink;
    }

    public function buildObject(array $oddLinkArr)
    {
        $oddLinkArr['odd_client'] = $this->clientRepository->createObject($oddLinkArr['odd_client']);
        return $this->createObject($oddLinkArr);
    }

    public function find($id)
    {
        return $this->getByPk($this->getModelClass(), [
            'id' => $id
        ], [
            $this,
            'buildObject'
        ], $this->getJoin());
    }

    /**
     *
     * @param string $clientCode
     * @return \Sportal\FootballApi\Model\OddLink[]
     */
    public function findByClient($clientCode)
    {
        return $this->findAll(
            [
                [
                    'key' => 'code',
                    'sign' => '=',
                    'value' => $clientCode,
                    'table' => $this->clientRepository->getPersistanceName($this->clientRepository->getModelClass())
                ]
            ]);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::findAll()
     * @return \Sportal\FootballApi\Model\OddLink[]
     */
    public function findAll($filter = [])
    {
        return $this->getAll($this->getModelClass(), $filter, [
            $this,
            'buildObject'
        ], $this->getJoin(), [
            [
                'key' => 'sortorder'
            ]
        ]);
    }

    /**
     *
     * @return \Sportal\FootballApi\Model\OddLink[]
     */
    public function getProviderIndex($clientCode = null)
    {
        $links = ($clientCode !== null) ? $this->findByClient($clientCode) : $this->findAll();

        $providerIndex = [];
        foreach ($links as $link) {
            $providerId = $link->getOddProviderId();
            if (! isset($providerIndex[$providerId])) {
                $providerIndex[$providerId] = [];
            }
            $providerIndex[$providerId][] = $link;
        }

        return $providerIndex;
    }

    public function getModelClass()
    {
        return OddLink::class;
    }

    public function getJoin()
    {
        static $join = null;
        if ($join === null) {
            $join = [
                [
                    'className' => $this->clientRepository->getModelClass(),
                    'type' => 'inner',
                    'columns' => $this->clientRepository->getColumns()
                ],
                [
                    'className' => OddProvider::class,
                    'type' => 'inner',
                    'columns' => OddProvider::COLUMNS
                ]
            ];
        }
        return $join;
    }
}