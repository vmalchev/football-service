<?php


namespace Sportal\FootballApi\Infrastructure\Blacklist;


use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistKey;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistKeyFactory;

class BlacklistKeyFactory implements IBlacklistKeyFactory
{
    private BlacklistType $type;

    private BlacklistEntityName $entity;

    private string $entityId;

    private ?string $context = null;

    public function setEmpty(): IBlacklistKeyFactory
    {
        return new BlacklistKeyFactory();
    }

    /**
     * @param BlacklistType $type
     * @return BlacklistKeyFactory
     */
    public function setType(BlacklistType $type): BlacklistKeyFactory
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param BlacklistEntityName $entity
     * @return BlacklistKeyFactory
     */
    public function setEntity(BlacklistEntityName $entity): BlacklistKeyFactory
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @param string $entityId
     * @return BlacklistKeyFactory
     */
    public function setEntityId(string $entityId): BlacklistKeyFactory
    {
        $this->entityId = $entityId;
        return $this;
    }

    /**
     * @param string|null $context
     * @return BlacklistKeyFactory
     */
    public function setContext(?string $context): BlacklistKeyFactory
    {
        $this->context = $context;
        return $this;
    }


    public function create(): IBlacklistKey
    {
        return new BlacklistKey($this->type, $this->entity, $this->entityId, $this->context);
    }
}