<?php


namespace Sportal\FootballApi\Infrastructure\Match;


use Sportal\FootballApi\Domain\Match\IMatchRefereeEntity;
use Sportal\FootballApi\Domain\Match\MatchRefereeRole;
use Sportal\FootballApi\Domain\Person\PersonGender;

class MatchRefereeEntity implements IMatchRefereeEntity
{
    private string $refereeId;

    private ?string $refereeName;

    private MatchRefereeRole $role;

    private ?PersonGender $gender;

    /**
     * MatchRefereeEntity constructor.
     * @param string $refereeId
     * @param string|null $refereeName
     * @param MatchRefereeRole $role
     * @param PersonGender|null $gender
     */
    public function __construct(string $refereeId, ?string $refereeName, MatchRefereeRole $role, ?PersonGender $gender)
    {
        $this->refereeId = $refereeId;
        $this->refereeName = $refereeName;
        $this->role = $role;
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function getRefereeId(): string
    {
        return $this->refereeId;
    }

    /**
     * @return string|null
     */
    public function getRefereeName(): ?string
    {
        return $this->refereeName;
    }

    /**
     * @return MatchRefereeRole
     */
    public function getRole(): MatchRefereeRole
    {
        return $this->role;
    }

    /**
     * @inheritDoc
     */
    public function getGender(): ?PersonGender
    {
        return $this->gender;
    }
}