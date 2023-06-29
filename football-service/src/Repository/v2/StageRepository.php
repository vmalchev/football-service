<?php
namespace Sportal\FootballApi\Repository\v2;

use Sportal\FootballApi\Database\Database;
use Sportal\FootballApi\Database\Query\Join;
use Sportal\FootballApi\Dto\Stage\StageListInput;
use Sportal\FootballApi\Entity\Country;
use Sportal\FootballApi\Entity\Season;
use Sportal\FootballApi\Entity\Stage;
use Sportal\FootballApi\Entity\Tournament;
use Sportal\FootballApi\Database\Query\SortDirection;

class StageRepository
{

    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     *
     * @param int $tournamentId
     * @return Stage[]
     */
    public function findBy(StageListInput $filter)
    {
        $query = $this->db->createQuery()
            ->from(Stage::TABLE_NAME)
            ->addJoin($this->createSeasonJoin());

        $queryExpr = $query->andX();

        if (! empty($filter->getTournamentIds())) {
            $queryExpr->in(Tournament::ID_FIELD, $filter->getTournamentIds(), Tournament::TABLE_NAME);
        }

        if (! empty($filter->getTeamId())) {
            $query->addJoin($this->createTeamJoin());
            $queryExpr->eq(Stage::TEAM_RELATION_ID_FIELD, $filter->getTeamId(), Stage::TEAM_RELATION_TABLE);
        }

        $query->where($queryExpr);
        $query->addOrderBy(Stage::START_DATE_FIELD, SortDirection::DESC);

        return $this->db->executeQuery($query, [
            Stage::class,
            'create'
        ]);
    }

    private function createTeamJoin(): Join
    {
        return $this->db->getJoinFactory()
            ->createInner(Stage::TEAM_RELATION_TABLE, [], Stage::ID_FIELD)
            ->setReference(Stage::TEAM_RELATION_REFERENCE);
    }

    private function createSeasonJoin(): Join
    {
        $factory = $this->db->getJoinFactory();
        return $factory->createInner(Season::TABLE_NAME, Season::columns())->addChild(
            $factory->createInner(Tournament::TABLE_NAME, Tournament::columns())
                ->addChild($factory->createInner(Country::TABLE_NAME, Country::columns())));
    }
}

