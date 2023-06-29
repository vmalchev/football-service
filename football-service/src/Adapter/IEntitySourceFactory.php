<?php
namespace Sportal\FootballApi\Adapter;


interface IEntitySourceFactory
{
    /**
     * @param EntityType $type
     * @return IEntitySource
     */
    public function getSource (EntityType $type): IEntitySource;
}