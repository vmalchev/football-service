<?php


namespace Sportal\FootballApi\Infrastructure\Blacklist;

use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistEntity;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistKey;
use Sportal\FootballApi\Infrastructure\Database\IDatabaseEntity;

final class Blacklist implements IBlacklistEntity, IDatabaseEntity
{
    const TABLE_NAME = "blacklist";
    const FIELD_ID = "id";
    const FIELD_TYPE = "type";
    const FIELD_ENTITY = "entity";
    const FIELD_ENTITY_ID = "entity_id";
    const FIELD_CONTEXT = "context";

    /**
     * @var string
     */
    private $id;

    /**
     * @var IBlacklistKey
     */
    private $blacklistKey;

    /**
     * Blacklist constructor.
     * @param $id
     * @param $blacklistKey
     */
    public function __construct(string $id, IBlacklistKey $blacklistKey = null)
    {
        $this->id = $id;
        $this->blacklistKey = $blacklistKey;
    }

    /**
     * @return IBlacklistKey
     */
    public function getBlacklistKey(): IBlacklistKey
    {
        return $this->blacklistKey;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function getDatabaseEntry(): array
    {
        return [
            self::FIELD_ID => $this->id,
            self::FIELD_TYPE => $this->blacklistKey->getType()->getValue(),
            self::FIELD_ENTITY => $this->blacklistKey->getEntity()->getValue(),
            self::FIELD_ENTITY_ID => $this->blacklistKey->getEntityId(),
            self::FIELD_CONTEXT => $this->blacklistKey->getContext()
        ];
    }

    public function getPrimaryKey(): array
    {
        return [
            self::FIELD_ID => $this->id
        ];
    }

    public static function create(array $data): Blacklist
    {
        return new Blacklist(
            $data[self::FIELD_ID],
            new BlacklistKey(
                new BlacklistType($data[self::FIELD_TYPE]),
                new BlacklistEntityName($data[self::FIELD_ENTITY]),
                $data[self::FIELD_ENTITY_ID],
                $data[self::FIELD_CONTEXT] ?? null
            )
        );
    }

}