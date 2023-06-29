<?php


namespace Sportal\FootballApi\Infrastructure\Lineup;


use LogicException;
use Sportal\FootballApi\Domain\Lineup\ILineupEntity;
use Sportal\FootballApi\Domain\Lineup\ILineupPlayerEntityFactory;
use Sportal\FootballApi\Domain\Lineup\ILineupPlayerRepository;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\DatabaseUpdate;
use Sportal\FootballApi\Infrastructure\LineupPlayerType\LineupPlayerTypeTableMapper;
use Sportal\FootballApi\Infrastructure\Player\PlayerTableMapper;

class LineupPlayerRepository implements ILineupPlayerRepository
{
    private Database $db;

    private PlayerTableMapper $playerTableMapper;

    private LineupPlayerTypeTableMapper $lineupPlayerTypeMapper;

    private ILineupPlayerEntityFactory $lineupPlayerFactory;

    /**
     * LineupPlayerRepository constructor.
     * @param Database $db
     * @param PlayerTableMapper $playerTableMapper
     * @param LineupPlayerTypeTableMapper $lineupPlayerTypeMapper
     * @param ILineupPlayerEntityFactory $lineupPlayerFactory
     */
    public function __construct(Database $db, PlayerTableMapper $playerTableMapper, LineupPlayerTypeTableMapper $lineupPlayerTypeMapper, ILineupPlayerEntityFactory $lineupPlayerFactory)
    {
        $this->db = $db;
        $this->playerTableMapper = $playerTableMapper;
        $this->lineupPlayerTypeMapper = $lineupPlayerTypeMapper;
        $this->lineupPlayerFactory = $lineupPlayerFactory;
    }


    /**
     * @inheritDoc
     */
    public function findByLineup(ILineupEntity $lineup): array
    {
        $query = $this->db->createQuery(LineupPlayerTable::TABLE_NAME)
            ->where($this->db->andExpression()->eq(LineupPlayerTable::FIELD_MATCH_ID, $lineup->getMatchId()))
            ->addJoin($this->playerTableMapper->getLeftJoin())
            ->addJoin($this->lineupPlayerTypeMapper->getInnerJoin());
        return $this->db->getQueryResults($query, [$this->lineupPlayerFactory, 'createFromArray']);
    }

    /**
     * @inheritDoc
     */
    public function upsertByLineup(ILineupEntity $lineup, array $players): void
    {
        $this->db->transactional(function (DatabaseUpdate $dbUpdate) use ($lineup, $players) {
            $dbUpdate->delete(LineupPlayerTable::TABLE_NAME, [LineupPlayerTable::FIELD_MATCH_ID => $lineup->getMatchId()]);
            foreach ($players as $player) {
                if (!$player instanceof LineupPlayerEntity) {
                    throw new LogicException("Repository used with invalid class" . get_class($player));
                }
                $dbUpdate->insertGeneratedId(LineupPlayerTable::TABLE_NAME, $player);
            }
        });
    }
}