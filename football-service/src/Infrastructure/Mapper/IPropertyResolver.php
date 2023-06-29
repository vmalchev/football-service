<?php


namespace Sportal\FootballApi\Infrastructure\Mapper;


interface IPropertyResolver
{
    public function resolve(object $object): array;
}