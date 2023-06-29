<?php
namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Model\OddProvider;
use Doctrine\DBAL\Connection;
use Sportal\FootballApi\Cache\CacheManager;

class OddProviderRepository extends Repository
{

    private $countryRepository;

    public function __construct(Connection $conn, CacheManager $cacheManager, CountryRepository $countryRepository)
    {
        parent::__construct($conn, $cacheManager);
        $this->countryRepository = $countryRepository;
    }

    public function createObject(array $oddProviderArr)
    {
        $oddProvider = new OddProvider();
        $oddProvider->setId($oddProviderArr['id'])->setName($oddProviderArr['name']);
        if (isset($oddProviderArr['country'])) {
            $oddProvider->setCountry($oddProviderArr['country']);
        }
        if (isset($oddProviderArr['url'])) {
            $oddProvider->setUrl($oddProviderArr['url']);
        }
        return $oddProvider;
    }

    public function buildObject(array $oddProviderArr)
    {
        $oddProviderArr['country'] = $this->countryRepository->createObject($oddProviderArr['country']);
        return $this->createObject($oddProviderArr);
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
     *
     * @see \Sportal\FootballApi\Repository\Repository::findAll()
     * @return OddProvider
     */
    public function findAll($filter = [])
    {
        return $this->getAll($this->getModelClass(), $filter, [
            $this,
            'buildObject'
        ], $this->getJoin());
    }

    public function getJoin()
    {
        static $join = null;
        if ($join === null) {
            $join = [
                [
                    'className' => $this->countryRepository->getModelClass(),
                    'type' => 'inner',
                    'columns' => $this->countryRepository->getColumns()
                ]
            ];
        }
        return $join;
    }

    public function getModelClass()
    {
        return OddProvider::class;
    }
}