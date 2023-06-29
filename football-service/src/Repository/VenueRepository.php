<?php

namespace Sportal\FootballApi\Repository;

use Doctrine\DBAL\Connection;
use Sportal\FootballApi\Cache\CacheManager;
use Sportal\FootballApi\Infrastructure\City\CityTable;
use Sportal\FootballApi\Model\City;
use Sportal\FootballApi\Model\ModelInterface;
use Sportal\FootballApi\Model\Venue;
use Sportal\FootballApi\Util\NameUtil;

/**
 * @author kstoilov
 *
 */
class VenueRepository extends Repository
{

    /**
     *
     * @var CountryRepository
     */
    private $countryRepository;

    private CityRepository $cityRepository;

    public function __construct(Connection        $conn,
                                CacheManager      $manager,
                                CountryRepository $countryRepository, CityRepository $cityRepository)
    {
        parent::__construct($conn,
            $manager);
        $this->countryRepository = $countryRepository;
        $this->cityRepository = $cityRepository;
    }

    public function createPartial(array $venueArr)
    {
        return (new Venue())->setId($venueArr['id'])->setName($venueArr['name']);
    }

    public function findExisting(Venue $match)
    {
        $venues = $this->queryPersistance(
            [
                'country_id' => $match->getCountry()->getId()
            ], [
            $this,
            'buildObject'
        ], $this->getJoin());

        foreach ($venues as $venue) {
            $nameExist = strtolower(NameUtil::normalizeName(preg_replace("/[^a-zA-Z0-9]+/", "", $venue->getName())));
            $nameMatch = strtolower(NameUtil::normalizeName(preg_replace("/[^a-zA-Z0-9]+/", "", $match->getName())));
            if (levenshtein($nameExist, $nameMatch) <= 1) {
                return $venue;
            }
        }
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
                ],
                [
                    'className' => City::class,
                    'type' => 'left',
                    'columns' => CityTable::getColumns()
                ],
            ];
        }
        return $join;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::find()
     */
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
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::getModelClass()
     */
    public function getModelClass()
    {
        return Venue::class;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::findAll()
     */
    public function findAll($filter = [])
    {
        return $this->getAll($this->getModelClass(), $filter, [
            $this,
            'buildObject'
        ]);
    }

    public function patchExisting(ModelInterface $existing, ModelInterface $updated)
    {
        $existing->setName($updated->getName());
        $existing->setCountry($updated->getCountry());
        $existing->setCityId($updated->getCityId());
        $existing->setCity($updated->getCity());
        $existing->setCityModel($updated->getCityModel());
        $existing->updateJsonColumns($updated);
        return $existing;
    }

    public function buildObject(array $venueArr)
    {
        $venueArr['country'] = $this->countryRepository->createObject($venueArr['country']);

        $cityModel = null;
        if (isset($venueArr['city']) && is_array($venueArr['city'])) {
            $cityModel = $this->cityRepository->createObject($venueArr['city']);
            $venueArr['city'] = $cityModel->getName();
        }

        return $this->createObject($venueArr)
            ->setCityModel($cityModel);
    }

    public function createObject(array $venueArr)
    {
        $venue = (new Venue())->setId($venueArr['id'])->setName($venueArr['name']);

        if (!isset($venueArr['country']) && isset($venueArr['country_id'])) {
            $venue->setCountry($this->countryRepository->find($venueArr['country_id']));
        } else {
            $venue->setCountry($venueArr['country']);
        }

        if (isset($venueArr['city'])) {
            $venue->setCity($venueArr['city']);
        }

        if (isset($venueArr['city_id'])) {
            $venue->setCityId($venueArr['city_id']);
        }

        if (isset($venueArr['profile'])) {
            $venue->setProfile(is_array($venueArr['profile']) ? $venueArr['profile'] : json_decode($venueArr['profile'], true));
        }

        return $venue;
    }
}