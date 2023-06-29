<?php
namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Adapter\IEntitySource;
use Sportal\FootballApi\Model\TournamentSeason;
use Doctrine\DBAL\Connection;
use Sportal\FootballApi\Cache\CacheManager;
use Sportal\FootballApi\Model\PartialTeam;

class TournamentSeasonRepository extends Repository implements IEntitySource
{

    const TEAMS_KEY = 'tournament_season_team';

    private $tournamentRepository;

    private $teamRepository;

    public function __construct(Connection $conn, CacheManager $cacheManager, TournamentRepository $tournamentRepository,
        TeamRepository $teamRepository)
    {
        parent::__construct($conn, $cacheManager);
        $this->tournamentRepository = $tournamentRepository;
        $this->teamRepository = $teamRepository;
    }

    /**
     *
     * @param array $tournamentSeasonArr
     * @return \Sportal\FootballApi\Model\TournamentSeason
     */
    public function createObject(array $tournamentSeasonArr)
    {
        $tournamentSeason = new TournamentSeason();
        $tournamentSeason->setId($tournamentSeasonArr['id']);
        $tournamentSeason->setName($tournamentSeasonArr['name']);
        $tournamentSeason->setActive($tournamentSeasonArr['active']);
        $tournamentSeason->setTournamentId($tournamentSeasonArr['tournament_id']);
        if (isset($tournamentSeasonArr['updated_at'])) {
            $tournamentSeason->setUpdatedAt(new \DateTime($tournamentSeasonArr['updated_at']));
        }
        if (isset($tournamentSeasonArr['tournament']) && is_array($tournamentSeasonArr['tournament'])) {
            $tournamentSeason->setTournament($this->tournamentRepository->build($tournamentSeasonArr['tournament']));
        }
        return $tournamentSeason;
    }

    public function getJoin()
    {
        return [
            [
                'className' => $this->tournamentRepository->getModelClass(),
                'type' => 'inner',
                'columns' => $this->tournamentRepository->getColumns(),
                'join' => $this->tournamentRepository->getJoin()
            ]
        ];
    }

    /**
     *
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::find()
     * @return \Sportal\FootballApi\Model\TournamentSeason
     */
    public function find($id)
    {
        $matching = $this->queryPersistance(array(
            'id' => $id
        ), array
        (
            $this,
            'createObject'
        ));

        if (count($matching) === 1) {
            return $matching[0];
        }

        return null;
    }

    public function findActive()
    {
        return $this->findAll([
            'active' => true
        ]);
    }

    /**
     *
     * @param integer $tournamentId
     * @return \Sportal\FootballApi\Model\TournamentSeason[]
     */
    public function findByTournament($tournamentId)
    {
        return $this->findAll([
            'tournament_id' => $tournamentId
        ]);
    }

    public function findExisting(TournamentSeason $match)
    {
        $seasons = $this->queryPersistance(
            [
                'tournament_id' => $match->getTournamentId(),
                'name' => (string) $match->getName()
            ], [
                $this,
                'createObject'
            ]);
        if (count($seasons) === 1) {
            return $seasons[0];
        }
        
        return null;
    }

    /**
     *
     * @see \Sportal\FootballApi\Repository\Repository::findAll()
     * @return \Sportal\FootballApi\Model\TournamentSeason[]
     */
    public function findAll($filter = array())
    {
        return $this->queryPersistance($filter,
            [
                $this,
                'createObject'
            ], [],
            [
                'keys' => [
                    'name'
                ],
                'direction' => 'DESC'
            ]);
    }

    public function getModelClass()
    {
        return TournamentSeason::class;
    }

    /**
     *
     * @param TournamentSeason $season
     * @param PartialTeam[] $teams
     */
    public function setTeams(TournamentSeason $season, array $teams)
    {
        $this->linkToMany($season, $teams, true);
    }

    public function findTeams($seasonId)
    {
        return $this->getTeamsPersistance($seasonId);
    }

    public function findByTeam($teamId)
    {
        return $this->getByTeamPersistance($teamId);
    }

    public function findLeagues($teamId)
    {
        return $this->getByTeamPersistance($teamId, true);
    }

    public function findLeague($teamId)
    {
        $seasons = $this->getByTeamPersistance($teamId, true, true);
        if (! empty($seasons)) {
            return $seasons[0];
        }
        return null;
    }

    protected function getByTeamPersistance($teamId, $league = false, $latest = false)
    {
        $seasonName = $this->getPersistanceName($this->getModelClass());
        $join = [
            [
                'className' => $this->getModelClass(),
                'type' => 'inner',
                'columns' => $this->getColumns(),
                'join' => $this->getJoin()
            ]
        ];
        
        $order = [
            [
                'object' => $seasonName,
                'key' => 'name',
                'direction' => 'desc'
            ],
            [
                'object' => $seasonName,
                'key' => 'tournament_id'
            ]
        ];
        
        $callback = function ($row) {
            return $this->createObject($row['tournament_season']);
        };
        
        $filter = [
            'team_id' => $teamId
        ];
        
        $limit = null;
        
        if ($league) {
            $filter[] = [
                'table' => $this->getPersistanceName($this->tournamentRepository->getModelClass()),
                'key' => 'regional_league',
                'sign' => '=',
                'value' => 1
            ];
            if ($latest) {
                $limit = [
                    'max_results' => 1
                ];
            }
        }
        
        return $this->queryTable(static::TEAMS_KEY, $filter, $callback, $join, $order, $limit);
    }

    protected function getTeamsPersistance($seasonId)
    {
        $join = [
            [
                'className' => $this->teamRepository->getModelClass(),
                'type' => 'inner',
                'columns' => $this->teamRepository->getPartialColumns()
            ]
        ];
        
        $order = [
            [
                'object' => $this->getPersistanceName($this->teamRepository->getModelClass()),
                'key' => 'name'
            ]
        ];
        
        return $this->queryTable(static::TEAMS_KEY, [
            'tournament_season_id' => $seasonId
        ], function ($row) {
            return $this->teamRepository->createPartial($row['team']);
        }, $join, $order);
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
            'createObject'
        ], $this->getJoin());
    }
}