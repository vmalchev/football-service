<?php


namespace Sportal\FootballApi\Infrastructure\StandingRule;


use Sportal\FootballApi\Domain\StandingRule\IStandingRuleRepository;
use Sportal\FootballApi\Infrastructure\Database\Database;

class StandingRuleRepository implements IStandingRuleRepository
{
    private Database $db;

    /**
     * StandingRuleRepository constructor.
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function existsById(string $id): bool
    {
        return $this->db->exists('standing_rule', ['id' => $id]);
    }
}