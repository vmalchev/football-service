<?php


namespace Sportal\FootballApi\Domain\Blacklist;


interface IBlacklistKeyFactory
{
    public function setEmpty(): IBlacklistKeyFactory;

    public function setType(BlacklistType $type): IBlacklistKeyFactory;

    public function setEntity(BlacklistEntityName $entity): IBlacklistKeyFactory;

    public function setEntityId(string $entityId): IBlacklistKeyFactory;

    public function setContext(?string $context): IBlacklistKeyFactory;

    public function create(): IBlacklistKey;
}