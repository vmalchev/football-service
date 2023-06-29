<?php

namespace Sportal\FootballApi\Infrastructure\Blacklist;

use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistKey;

class BlacklistKey implements IBlacklistKey
{
    /**
     * @var BlacklistType
     */
    private $type;

    /**
     * @var BlacklistEntityName
     */
    private $entity;

    private $entityId;

    private $context;

    /**
     * BlacklistKey constructor.
     * @param BlacklistType $type
     * @param BlacklistEntityName $entity
     * @param $entityId
     * @param $context
     */
    public function __construct(BlacklistType $type, BlacklistEntityName $entity, $entityId, $context = null)
    {
        $this->type = $type;
        $this->entity = $entity;
        $this->entityId = $entityId;
        $this->context = $context;
    }


    /**
     * @return BlacklistType
     */
    public function getType(): BlacklistType
    {
        return $this->type;
    }

    /**
     * @return BlacklistEntityName
     */
    public function getEntity(): BlacklistEntityName
    {
        return $this->entity;
    }

    /**
     * @return string
     */
    public function getEntityId(): string
    {
        return $this->entityId;
    }

    /**
     * @return string
     */
    public function getContext(): ?string
    {
        return $this->context;
    }

}