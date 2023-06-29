<?php

namespace Sportal\FootballApi\Domain\Blacklist;

interface IBlacklistEntity
{
    public function getId(): string;

    public function getBlacklistKey(): IBlacklistKey;
}