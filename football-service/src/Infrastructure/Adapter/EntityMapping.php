<?php
namespace Sportal\FootballApi\Infrastructure\Adapter;


use Sportal\FootballApi\Adapter\EntityType;
use Sportal\FootballApi\Adapter\IEntityMapping;
use Sportal\FootballApi\Adapter\Provider;

class EntityMapping implements IEntityMapping
{
    /**
     * @var Provider
     */
    private $provider;

    /**
     * @var EntityType
     */
    private $entityType;

    /**
     * @var
     */
    private $domainId;

    /**
     * @var integer
     */
    private $feedId;

    /**
     * @param Provider $provider
     * @param EntityType $entityType
     * @param $domainId
     * @param int $feedId
     */
    public function __construct(Provider $provider, EntityType $entityType, $domainId, $feedId)
    {
        $this->provider = $provider;
        $this->entityType = $entityType;
        $this->domainId = $domainId;
        $this->feedId = $feedId;
    }


    public function getEntityType(): EntityType
    {
        return $this->entityType;
    }

    public function getDomainId(): int
    {
        return $this->domainId;
    }

    public function getFeedId(): int
    {
        return $this->feedId;
    }

    public function getProvider(): Provider
    {
        return $this->provider;
    }
}
