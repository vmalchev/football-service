<?php
namespace Sportal\FootballApi\Adapter;

interface IEntityMapping
{
    /**
     * @return Provider
     */
    public function getProvider(): Provider;

    /**
     * @return EntityType
     */
    public function getEntityType(): EntityType;

    /**
     * @return integer
     */
    public function getDomainId(): int;

    /**
     * @return integer
     */
    public function getFeedId(): int;
}