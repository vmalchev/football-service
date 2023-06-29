<?php
namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Repository\TeamPersonRepository;
use Doctrine\DBAL\Connection;
use Sportal\FootballApi\Cache\CacheManager;
use Sportal\FootballApi\Model\TeamCoach;

class TeamCoachRepository extends TeamPersonRepository
{

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\TeamPersonRepository::__construct()
     */
    public function __construct(Connection $conn, CacheManager $cacheManager, TeamRepository $teamRepository,
        CoachRepository $personRepository)
    {
        // TODO Auto-generated method stub
        parent::__construct($conn, $cacheManager, $teamRepository, $personRepository);
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::getModelClass()
     */
    public function getModelClass()
    {
        return TeamCoach::class;
    }

    public function getCurrent($teamId)
    {
        $coaches = parent::getCurrent($teamId);
        if (count($coaches) === 1) {
            return $coaches[0];
        }
        return null;
    }
}