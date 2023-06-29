<?php


namespace Sportal\FootballApi\Domain\Match;


use Sportal\FootballApi\Domain\Person\PersonGender;

interface IMatchRefereeEntity
{
    public function getRefereeId(): string;

    public function getRefereeName(): ?string;

    public function getRole(): MatchRefereeRole;

    /**
     * @return PersonGender|null
     */
    public function getGender(): ?PersonGender;
}