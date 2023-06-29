<?php


namespace Sportal\FootballApi\Domain\Blacklist;

interface IBlacklistKey
{
    public function getType(): BlacklistType;

    public function getEntity(): BlacklistEntityName;

    public function getEntityId(): string;

    public function getContext(): ?string;
}