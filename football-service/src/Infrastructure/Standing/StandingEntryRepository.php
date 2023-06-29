<?php


namespace Sportal\FootballApi\Infrastructure\Standing;


use InvalidArgumentException;
use Sportal\FootballApi\Domain\Standing\IStandingEntity;
use Sportal\FootballApi\Domain\Standing\IStandingEntry;
use Sportal\FootballApi\Domain\Standing\IStandingEntryRepository;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\DatabaseUpdate;

class StandingEntryRepository implements IStandingEntryRepository
{

    private Database $db;

    /**
     * StandingEntryRepository constructor.
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }


    /**
     * @param IStandingEntity $standingEntity
     * @param IStandingEntry[] $entries
     * @return IStandingEntry[] entries as stored in persistence
     */
    public function upsert(IStandingEntity $standingEntity, array $entries): array
    {
        if ($standingEntity->getId() === null) {
            throw new InvalidArgumentException("StandingEntity must have an id");
        }
        return $this->db->transactional(function (DatabaseUpdate $dbUpdate) use ($standingEntity, $entries) {
            $dbUpdate->delete(StandingEntryTableMapper::TABLE_NAME, [StandingEntryTableMapper::FIELD_STANDING_ID => $standingEntity->getId()]);
            $updatedEntries = [];
            foreach ($entries as $entry) {
                if ($entry instanceof LeagueStandingEntry || $entry instanceof TopScorerEntry) {
                    $updatedEntry = $entry->withStandingId($standingEntity->getId());
                    $dbUpdate->insert(StandingEntryTableMapper::TABLE_NAME, $updatedEntry);
                    $updatedEntries[] = $updatedEntry;
                } else {
                    throw new InvalidArgumentException("Entity with type " . get_class($entry) . " is not supported");
                }
            }
            return $updatedEntries;
        });
    }

}