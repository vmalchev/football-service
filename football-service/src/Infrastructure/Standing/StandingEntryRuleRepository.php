<?php


namespace Sportal\FootballApi\Infrastructure\Standing;


use InvalidArgumentException;
use Sportal\FootballApi\Domain\Standing\IStandingEntity;
use Sportal\FootballApi\Domain\Standing\IStandingEntryRuleRepository;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\DatabaseUpdate;

class StandingEntryRuleRepository implements IStandingEntryRuleRepository
{
    private Database $db;

    /**
     * StandingEntryRuleRepository constructor.
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }


    /**
     * @inheritDoc
     */
    public function upsertByStanding(IStandingEntity $standingEntity, array $standingEntryRules): array
    {
        if ($standingEntity->getId() === null) {
            throw new InvalidArgumentException("StandingEntity must have an id");
        }
        return $this->db->transactional(function (DatabaseUpdate $dbUpdate) use ($standingEntity, $standingEntryRules) {
            $dbUpdate->delete(StandingEntryRuleTableMapper::TABLE_NAME, [StandingEntryRuleTableMapper::FIELD_STANDING_ID => $standingEntity->getId()]);
            $entryRules = [];
            foreach ($standingEntryRules as $entryRule) {
                if (!$entryRule instanceof StandingEntryRule) {
                    throw new InvalidArgumentException("Unsupported entity class:" . get_class($entryRule));
                }
                $entryRules[] = $dbUpdate->insertGeneratedId(StandingEntryRuleTableMapper::TABLE_NAME, $entryRule);
            }
            return $entryRules;
        });
    }
}