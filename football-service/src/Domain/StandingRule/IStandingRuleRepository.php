<?php


namespace Sportal\FootballApi\Domain\StandingRule;


interface IStandingRuleRepository
{
    public function existsById(string $id): bool;
}