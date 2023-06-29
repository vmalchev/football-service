<?php


namespace Sportal\FootballApi\Domain\KnockoutScheme;

use Sportal\FootballApi\Domain\Team\ITeamEntity;

interface IKnockoutGroupEntity
{
    public function getId(): string;

    public function getOrder(): int;

    /**
     * @return ITeamEntity[]
     */
    public function getTeams(): array;

    /**
     * @return IKnockoutMatchEntity[]
     */
    public function getMatches(): array;

    public function getChildObjectId(): ?string;
}