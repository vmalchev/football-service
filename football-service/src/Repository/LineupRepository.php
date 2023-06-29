<?php
namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Database\Database;
use Sportal\FootballApi\Database\ModelInterface;
use Sportal\FootballApi\Database\Query\SortDirection;
use Sportal\FootballApi\Model\EventPlayerType;
use Sportal\FootballApi\Model\Lineup;
use Sportal\FootballApi\Database\Query\Query;
use Sportal\FootballApi\Model\Event;
use Sportal\FootballApi\Model\PartialPerson;

class LineupRepository
{
    private $db;

    private $coachRepository;

    private $eventPlayerRepository;

    public function __construct(Database $db, CoachRepository $coachRepository,
        EventPlayerRepository $eventPlayerRepository)
    {
        $this->db = $db;
        $this->coachRepository = $coachRepository;
        $this->eventPlayerRepository = $eventPlayerRepository;
    }

    /**
     *
     * @param array $lineupArr
     * @return \Sportal\FootballApi\Model\Lineup
     */
    public function createObject(array $lineupArr)
    {
        $lineup = new Lineup();
        $lineup->setEventId($lineupArr['event_id']);
        if (isset($lineupArr['home_formation'])) {
            $lineup->setHomeFormation($lineupArr['home_formation']);
        }
        if (isset($lineupArr['away_formation'])) {
            $lineup->setAwayFormation($lineupArr['away_formation']);
        }
        if (isset($lineupArr['home_coach'])) {
            $lineup->setHomeCoach($lineupArr['home_coach']);
        }
        if (isset($lineupArr['away_coach'])) {
            $lineup->setAwayCoach($lineupArr['away_coach']);
        }
        if (isset($lineupArr['confirmed'])) {
            $lineup->setConfirmed($lineupArr['confirmed']);
        }
        if (isset($lineupArr['updated_at'])) {
            $lineup->setUpdatedAt(new \DateTime($lineupArr['updated_at']));
        }
        if (isset($lineupArr['home_team_id'])) {
            $lineup->setHomeTeamId($lineupArr['home_team_id']);
        }
        if (isset($lineupArr['away_team_id'])) {
            $lineup->setAwayTeamId($lineupArr['away_team_id']);
        }
        return $lineup;
    }

    public function buildObject(array $lineupArr)
    {
        if (isset($lineupArr['home_coach'])) {
            $lineupArr['home_coach'] = $this->coachRepository->createPartialObject($lineupArr['home_coach']);
        }
        if (isset($lineupArr['away_coach'])) {
            $lineupArr['away_coach'] = $this->coachRepository->createPartialObject($lineupArr['away_coach']);
        }
        
        $lineupArr['home_team_id'] = $lineupArr['event']['home_id'];
        $lineupArr['away_team_id'] = $lineupArr['event']['away_id'];
        
        return $this->createObject($lineupArr);
    }

    public function findUpdatedAfter(\DateTime $after)
    {
        $query = $this->db->createQuery();
        $query->where($query->andX()->gteq(Lineup::UPDATED_AT_INDEX, $this->db->formatTime($after)))
            ->addOrderBy(Lineup::UPDATED_AT_INDEX, SortDirection::DESC);

        return $this->queryDatabase($query);
    }

    /**
     *
     * @param Lineup[] $lineups
     */
    public function addPlayers(array $lineups)
    {
        foreach ($lineups as $lineup) {
            $players = $this->eventPlayerRepository->findByEvent($lineup->getEventId());
            foreach ($players as $player) {
                $lineup->addPlayer($player);
            }
        }
    }

    /**
     *
     * @param unknown $eventId
     * @return Lineup
     */
    public function find($eventId)
    {
        $query = $this->db->createQuery()->whereEquals('event_id', $eventId);
        $result = $this->queryDatabase($query);

        return (! empty($result)) ? $result[0] : null;
    }

    public function updateCoach($eventId, PartialPerson $coach, $homeTeam)
    {
        $lineup = $this->find($eventId);

        if ($lineup !== null) {
            if ($homeTeam) {
                $lineup->setHomeCoach($coach);
            } else {
                $lineup->setAwayCoach($coach);
            }
            $this->db->update($lineup);
            $this->db->flush();
        }
    }

    public function getModelClass()
    {
        return Lineup::class;
    }

    protected function queryDatabase(Query $query)
    {
        $query->from($this->getModelClass())
            ->addJoinList($this->getJoinList());
        
        return $this->db->executeQuery($query, [
            $this,
            'buildObject'
        ]);
    }

    protected function getJoinList()
    {
        $factory = $this->db->getJoinFactory();
        return [
            $factory->createLeft($this->coachRepository->getModelClass(), $this->coachRepository->getColumns(),
                'home_coach_id', 'home_coach'),
            $factory->createLeft($this->coachRepository->getModelClass(), $this->coachRepository->getColumns(),
                'away_coach_id', 'away_coach'),
            $factory->createInner(Event::class, [
                'home_id',
                'away_id'
            ])
        ];
    }

    public function create($lineup)
    {
        $this->db->insert($lineup);
    }

    public function update($existing, $updated)
    {
        $this->db->update($updated);
    }

    public function delete($model)
    {
        $this->db->delete($model);
    }

    public function flush()
    {
        $this->db->flush();
    }

    public function getChanges(ModelInterface $existing, ModelInterface $updated)
    {
        $changed = [];
        $updatedMap = $updated->getPersistanceMap();
        foreach ($existing->getPersistanceMap() as $key => $value) {
            if ($updatedMap[$key] != $value) {
                $changed[] = $key;
            }
        }

        return $changed;
    }
}