<?php

namespace Sportal\FootballApi\Repository;

use Doctrine\DBAL\Connection;
use Sportal\FootballApi\Cache\CacheManager;
use Sportal\FootballApi\Model\Tournament;

class TournamentRepository extends Repository
{

    /**
     *
     * @var CountryRepository
     */
    private $countryRepository;

    private $tournamentOrder;

    public function __construct(Connection                $conn, CacheManager $manager, CountryRepository $countryRepository,
                                TournamentOrderRepository $tournamentOrder)
    {
        parent::__construct($conn, $manager);
        $this->countryRepository = $countryRepository;
        $this->tournamentOrder = $tournamentOrder;
    }

    /**
     *
     * @param array $tournamentArr
     * @return \Sportal\FootballApi\Model\Tournament
     */
    public function createObject(array $tournamentArr)
    {
        $tournament = new Tournament();
        $tournament->setName($tournamentArr['name']);
        $tournament->setId($tournamentArr['id']);
        $tournament->setCountry($tournamentArr['country']);

        if (isset($tournamentArr['gender'])) {
            $tournament->setGender($tournamentArr['gender']);
        }

        if (isset($tournamentArr['type'])) {
            $tournament->setType($tournamentArr['type']);
        }

        if (isset($tournamentArr['region'])) {
            $tournament->setRegion($tournamentArr['region']);
        }

        if (isset($tournamentArr['updated_at'])) {
            $tournament->setUpdatedAt(new \DateTime($tournamentArr['updated_at']));
        }
        if (isset($tournamentArr['regional_league'])) {
            $tournament->setRegionalLeague($tournamentArr['regional_league']);
        }
        return $tournament;
    }

    public function build(array $tournamentArr)
    {
        $tournamentArr['country'] = $this->countryRepository->createObject($tournamentArr['country']);
        $tournament = $this->createObject($tournamentArr);
        return $tournament;
    }

    public function findByCountry($countryId)
    {
        return $this->findAll([
            'country_id' => $countryId
        ]);
    }

    public function findByClient($clientCode)
    {
        $all = $this->findAll();
        return $this->tournamentOrder->orderTournaments($all, $clientCode);
    }

    /**
     *
     * {@inheritDoc}
     * @return \Sportal\FootballApi\Model\Tournament[]
     * @see \Sportal\FootballApi\Repository\Repository::findAll()
     */
    public function findAll($filter = array())
    {
        return $this->getAll($this->getModelClass(), $filter, [
            $this,
            'build'
        ], $this->getJoin(),
            [
                [
                    'object' => $this->getPersistanceName($this->countryRepository->getModelClass()),
                    'key' => 'name'
                ],
                [
                    'key' => 'id'
                ]
            ]);
    }

    /**
     * Get a Tournament by primary key
     * @param int $id
     * @return \Sportal\FootballApi\Model\Tournament
     */
    public function find($id)
    {
        return $this->getByPk($this->getModelClass(), [
            'id' => $id
        ], [
            $this,
            'build'
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
        return Tournament::class;
    }
}