<?php

namespace Sportal\FootballApi\Repository;

use Doctrine\DBAL\Connection;
use Sportal\FootballApi\Cache\CacheManager;
use Sportal\FootballApi\Model\Player;
use Sportal\FootballApi\Model\TeamPlayer;

class TeamPlayerRepository extends TeamPersonRepository
{

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::__construct()
     */
    public function __construct(Connection $conn, CacheManager $cacheManager, TeamRepository $teamRepository, PlayerRepository $personRepository)
    {
        parent::__construct($conn, $cacheManager, $teamRepository, $personRepository);
    }

    public function createObject(array $data)
    {
        $object = parent::createObject($data);

        if (isset($data['shirt_number'])) {
            $object->setShirtNumber($data['shirt_number']);
        }

        if (isset($data['loan'])) {
            $object->setLoan($data['loan']);
        }

        if (isset($data['sortorder'])) {
            $object->setSortorder($data['sortorder']);
        }

        return $object;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::getModelClass()
     */
    public function getModelClass()
    {
        return TeamPlayer::class;
    }

    /**
     *
     * @param integer $teamId
     * @return \Sportal\FootballApi\Model\TeamPlayer
     */
    public function getCurrent($teamId)
    {
        return $this->findAll([
            'team_id' => $teamId,
            'active' => 1
        ], [
            [
                'key' => 'shirt_number'
            ]
        ]);
    }

    /**
     *
     * @param integer $playerId
     * @return \Sportal\FootballApi\Model\TeamPerson[]
     */
    public function findTeams($playerId)
    {
        $teams = $this->findAll([
            'player_id' => $playerId
        ], [
            [
                'key' => 'sortorder',
                'direction' => 'desc'
            ]
        ]);

        return $teams;
    }

    public function findClub($playerId)
    {
        $teamPlayers = $this->findAll(
            [
                'player_id' => $playerId,
                'active' => 1,
                [
                    'table' => $this->getPersistanceName($this->teamRepository->getModelClass()),
                    'key' => 'national',
                    'sign' => '=',
                    'value' => 0
                ]
            ]);

        if (count($teamPlayers) == 1) {
            return $teamPlayers[0];
        } elseif (!empty($teamPlayers)) {
            foreach ($teamPlayers as $teamPlayer) {
                if ($teamPlayer->getLoan()) {
                    return $teamPlayer;
                }
            }
        }
    }

    public function findNationalTeam($playerId)
    {
        $teamPlayer = $this->findAll(
            [
                'player_id' => $playerId,
                'active' => 1,
                [
                    'table' => $this->getPersistanceName($this->teamRepository->getModelClass()),
                    'key' => 'national',
                    'sign' => '=',
                    'value' => 1
                ]
            ]);

        return $teamPlayer[0] ?? null;
    }
}