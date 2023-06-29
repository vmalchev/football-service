<?php


namespace Sportal\FootballApi\Domain\KnockoutScheme;


use Sportal\FootballApi\Domain\Match\IMatchScore;

interface IKnockoutMatchEntity
{

    /**
     * @return ?string
     */
    public function getId(): ?string;

    /**
     * @return string
     */
    public function getKickOffTime(): string;

    public function getScore(): ?IMatchScore;

    public function getHomeTeamId(): ?string;

    public function getAwayTeamId(): ?string;
}