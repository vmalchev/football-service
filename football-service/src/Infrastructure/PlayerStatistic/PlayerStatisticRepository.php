<?php


namespace Sportal\FootballApi\Infrastructure\PlayerStatistic;


use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerStatisticEntity;
use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerStatisticEntityFactory;
use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerStatisticRepository;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\DatabaseUpdate;

class PlayerStatisticRepository implements IPlayerStatisticRepository
{
    /**
     * @var Database
     */
    private Database $db;

    /**
     * @var DatabaseUpdate
     */
    private DatabaseUpdate $dbUpdate;

    /**
     * @var IPlayerStatisticEntityFactory
     */
    private IPlayerStatisticEntityFactory $playerStatisticFactory;

    /**
     * @param Database $db
     * @param DatabaseUpdate $dbUpdate
     * @param IPlayerStatisticEntityFactory $playerStatisticFactory
     */
    public function __construct(Database $db, DatabaseUpdate $dbUpdate, IPlayerStatisticEntityFactory $playerStatisticFactory)
    {
        $this->db = $db;
        $this->dbUpdate = $dbUpdate;
        $this->playerStatisticFactory = $playerStatisticFactory;
    }

    /**
     * @param IPlayerStatisticEntity $playerStatisticEntity
     * @return IPlayerStatisticEntity
     */
    public function insert(IPlayerStatisticEntity $playerStatisticEntity): IPlayerStatisticEntity
    {
        $this->dbUpdate->insert(PlayerStatisticTable::TABLE_NAME, $playerStatisticEntity);

        return $playerStatisticEntity;
    }

    /**
     * @param IPlayerStatisticEntity $playerStatisticEntity
     * @return IPlayerStatisticEntity
     */
    public function update(IPlayerStatisticEntity $playerStatisticEntity): IPlayerStatisticEntity
    {
        $this->dbUpdate->update(PlayerStatisticTable::TABLE_NAME, $playerStatisticEntity);

        return $playerStatisticEntity;
    }

    /**
     * @param IPlayerStatisticEntity $playerStatisticEntity
     * @return IPlayerStatisticEntity|null
     */
    public function find(IPlayerStatisticEntity $playerStatisticEntity): ?IPlayerStatisticEntity
    {
        $andExpr = $this->db->andExpression()
            ->eq(PlayerStatisticTable::FIELD_PLAYER_ID, $playerStatisticEntity->getPlayerId())
            ->eq(PlayerStatisticTable::FIELD_MATCH_ID, $playerStatisticEntity->getMatchId())
            ->eq(PlayerStatisticTable::FIELD_ORIGIN, $playerStatisticEntity->getOrigin());

        $query = $this->db->createQuery(PlayerStatisticTable::TABLE_NAME)->where($andExpr);

        return $this->db->getSingleResult($query, [$this->playerStatisticFactory, "createFromArray"]);
    }

    public function deleteByMatchId(string $matchId): void
    {
        $this->dbUpdate->delete(
            PlayerStatisticTable::TABLE_NAME, [PlayerStatisticTable::FIELD_MATCH_ID => $matchId]
        );
    }

    /**
     * @inheritDoc
     */
    public function upsertByMatchId(string $matchId, array $playerStatisticEntities)
    {
        $this->db->transactional(function () use ($matchId, $playerStatisticEntities) {
            $this->deleteByMatchId($matchId);

            foreach ($playerStatisticEntities as $entity) {
                $this->insert($entity);
            }
        });
    }

}