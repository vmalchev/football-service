<?php
namespace Sportal\FootballApi\Repository;

use Doctrine\DBAL\Connection;
use Sportal\FootballApi\Adapter\IEntitySource;
use Sportal\FootballApi\Cache\CacheManager;
use Sportal\FootballApi\Model\PartialPerson;
use Sportal\FootballApi\Model\Person;
use Sportal\FootballApi\Util\NameUtil;

abstract class PersonRepository extends Repository implements IEntitySource
{

    /**
     *
     * @var MlContentRepository
     */
    protected $mlContent;

    /**
     *
     * @var CountryRepository
     */
    protected $countryRepository;

    public function __construct(Connection $conn, CacheManager $cacheManager, MlContentRepository $mlContent, CountryRepository $countryRepository)
    {
        parent::__construct($conn, $cacheManager);
        $this->countryRepository = $countryRepository;
        $this->mlContent = $mlContent;
    }

    public function createObject(array $playerArr)
    {
        $className = $this->getModelClass();
        $object = (new $className())->setId($playerArr['id'])
            ->setName($playerArr['name'])
            ->setCountry($playerArr['country']);

        if (isset($playerArr['birthdate'])) {
            $object->setBirthdate(new \DateTime($playerArr['birthdate']));
        }

        if (isset($playerArr['first_name'])) {
            $object->setFirstName($playerArr['first_name']);
        }
        if (isset($playerArr['last_name'])) {
            $object->setLastName($playerArr['last_name']);
        }

        if (isset($playerArr['active'])) {
            $object->setActive($playerArr['active']);
        }

        if (isset($playerArr['gender'])) {
            $object->setGender($playerArr['gender']);
        }

        return $object;
    }

    public function createPartialObject(array $data)
    {
        $object = (new PartialPerson())->setId($data['id'])->setName($data['name']);
        return $object;
    }

    /**
     *
     * @param Person $player
     * @return \Sportal\FootballApi\Model\Person
     */
    public function clonePartial(Person $player)
    {
        $className = $this->getModelClass();
        $object = (new $className())->setName($player->getName())
            ->setId($player->getId());
        return $object;
    }

    public function buildObject(array $playerArr)
    {
        $playerArr['country'] = $this->countryRepository->createObject($playerArr['country']);
        return $this->createObject($playerArr);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::find()
     * @return \Sportal\FootballApi\Model\Person
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

    public function findAll($filter = [])
    {
        return $this->getAll($this->getModelClass(), $filter, [
            $this,
            'buildObject'
        ], $this->getJoin());
    }

    public function findExisting(Person $match)
    {
        if ($match->getBirthdate() !== null) {
            $filter = [
                'country_id' => $match->getCountry()->getId(),
                'birthdate' => $match->getBirthdate()->format('Y-m-d')
            ];
            $players = $this->queryPersistance($filter, [
                $this,
                'buildObject'
            ], $this->getJoin());
            
            return NameUtil::findNameMatch($players, $match);
        } else {
            return null;
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
                ]
            ];
        }
        return $join;
    }

    public function getPartialFromFeed($feedId, MappingRepositoryInterface $mapping)
    {
        $personId = $mapping->getOwnId($this->getModelClass(), $feedId);
        if ($personId !== null && ($person = $this->find($personId)) !== null) {
            return $person->clonePartial();
        }
        return null;
    }

    protected function getIgnoredKeys()
    {
        $default = parent::getIgnoredKeys();
        $default[] = 'name';
        return $default;
    }

    public function findByIds(array $ids): array
    {
        $filter = [
            [
                'key' => 'id',
                'sign' => 'in',
                'value' => $ids,
            ]
        ];

        $tableName = $this->getPersistanceName($this->getModelClass());

        return $this->queryTable($tableName, $filter, [
            $this,
            'createPartialObject'
        ]);
    }
}