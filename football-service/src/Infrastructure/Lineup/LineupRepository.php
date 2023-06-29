<?php


namespace Sportal\FootballApi\Infrastructure\Lineup;


use LogicException;
use Sportal\FootballApi\Domain\Lineup\ILineupEntity;
use Sportal\FootballApi\Domain\Lineup\ILineupEntityFactory;
use Sportal\FootballApi\Domain\Lineup\ILineupRepository;
use Sportal\FootballApi\Infrastructure\Coach\CoachTableMapper;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\DatabaseUpdate;
use Sportal\FootballApi\Infrastructure\Match\MatchTable;

class LineupRepository implements ILineupRepository
{
    private Database $db;
    private DatabaseUpdate $dbUpdate;
    private ILineupEntityFactory $lineupFactory;
    private CoachTableMapper $coachTableMapper;

    /**
     * LineupRepository constructor.
     * @param Database $db
     * @param ILineupEntityFactory $lineupFactory
     * @param CoachTableMapper $coachTableMapper
     * @param DatabaseUpdate $dbUpdate
     */
    public function __construct(Database $db, ILineupEntityFactory $lineupFactory, CoachTableMapper $coachTableMapper, DatabaseUpdate $dbUpdate)
    {
        $this->db = $db;
        $this->lineupFactory = $lineupFactory;
        $this->coachTableMapper = $coachTableMapper;
        $this->dbUpdate = $dbUpdate;
    }


    public function findByMatchId(string $matchId): ?ILineupEntity
    {
        $query = $this->db->createQuery(LineupTable::TABLE_NAME)
            ->where($this->db->andExpression()->eq(LineupTable::FIELD_MATCH_ID, $matchId))
            ->addJoin($this->coachTableMapper->getLeftJoin()->setForeignKey(LineupTable::FIELD_HOME_COACH_ID)->setObjectName(LineupTable::FIELD_HOME_COACH))
            ->addJoin($this->coachTableMapper->getLeftJoin()->setForeignKey(LineupTable::FIELD_AWAY_COACH_ID)->setObjectName(LineupTable::FIELD_AWAY_COACH))
            ->addJoin($this->db->getJoinFactory()->createInner(MatchTable::TABLE_NAME, MatchTable::getColumns(), LineupTable::FIELD_MATCH_ID));

        return $this->db->getSingleResult($query, [$this->lineupFactory, 'createFromArray']);
    }

    public function upsert(ILineupEntity $lineup): void
    {
        if (!$lineup instanceof LineupEntity) {
            throw new LogicException(get_class($lineup) . " entity is not supported by LineupRepository");
        }
        if ($this->db->exists(LineupTable::TABLE_NAME, [LineupTable::FIELD_MATCH_ID => $lineup->getMatchId()])) {
            $this->dbUpdate->update(LineupTable::TABLE_NAME, $lineup);
        } else {
            $this->dbUpdate->insert(LineupTable::TABLE_NAME, $lineup);
        }
    }
}
