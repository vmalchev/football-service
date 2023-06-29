<?php


namespace Sportal\FootballApi\Domain\Standing;


interface IStandingEntryRule
{
    public function getStandingId(): string;

    public function getStandingRuleId(): string;

    public function getRank(): int;
}