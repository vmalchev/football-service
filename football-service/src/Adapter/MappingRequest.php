<?php


namespace Sportal\FootballApi\Adapter;


class MappingRequest
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
     * @var integer
     */
    private $id;

    public function __construct(Provider $provider, EntityType $entityType, $id)
    {
        $this->provider = $provider;
        $this->entityType = $entityType;
        $this->id = $id;
    }

    /**
     * @return Provider
     */
    public function getProvider(): Provider
    {
        return $this->provider;
    }

    /**
     * @return EntityType
     */
    public function getEntityType(): EntityType
    {
        return $this->entityType;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}