<?php

namespace Sportal\FootballApi\Application\Blacklist\Dto;

use JsonSerializable;
use Sportal\FootballApi\Infrastructure\Blacklist\Blacklist;

/**
 * @SWG\Definition()
 */
class BlacklistDto implements JsonSerializable
{
    /**
     * @SWG\Property()
     * @var string
     */
    private $id;

    /**
     * @SWG\Property()
     * @var BlacklistKeyDto
     */
    private $key;

    /**
     * BlacklistDto constructor.
     * @param $id
     * @param $blacklistKeyDto
     */
    public function __construct($id, BlacklistKeyDto $blacklistKeyDto)
    {
        $this->id = $id;
        $this->key = $blacklistKeyDto;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return BlacklistKeyDto
     */
    public function getBlacklistKeyDto(): BlacklistKeyDto
    {
        return $this->key;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * @param Blacklist $entity
     * @return BlacklistDto
     */
    public static function fromBlacklistEntity($entity): BlacklistDto
    {
        return new BlacklistDto(
            $entity->getId(),
            new BlacklistKeyDto(
                $entity->getBlacklistKey()->getType()->getKey(),
                $entity->getBlacklistKey()->getEntity()->getKey(),
                $entity->getBlacklistKey()->getEntityId(),
                $entity->getBlacklistKey()->getContext()
            )
        );
    }

}