<?php

namespace Sportal\FootballApi\Domain\Match;

use Sportal\FootballApi\Domain\Person\PersonGender;

interface IMatchRefereeEntityFactory
{
    /**
     * @param string $refereeId
     * @return IMatchRefereeEntityFactory
     */
    public function setRefereeId(string $refereeId): IMatchRefereeEntityFactory;

    /**
     * @param string|null $refereeName
     * @return IMatchRefereeEntityFactory
     */
    public function setRefereeName(?string $refereeName): IMatchRefereeEntityFactory;

    /**
     * @param MatchRefereeRole $role
     * @return IMatchRefereeEntityFactory
     */
    public function setRole(MatchRefereeRole $role): IMatchRefereeEntityFactory;

    /**
     * @param PersonGender|null $gender
     * @return IMatchRefereeEntityFactory
     */
    public function setGender(?PersonGender $gender): IMatchRefereeEntityFactory;

    public function setEmpty(): IMatchRefereeEntityFactory;

    public function setFrom(IMatchRefereeEntity $refereeEntity): IMatchRefereeEntityFactory;

    public function create(): IMatchRefereeEntity;
}