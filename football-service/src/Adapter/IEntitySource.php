<?php


namespace Sportal\FootballApi\Adapter;


interface IEntitySource
{
    /**
     * @param array $ids
     * @return array
     */
    public function findByIds(array $ids): array;
}