<?php


namespace Sportal\FootballApi\Infrastructure\Match;


use Sportal\FootballApi\Domain\Match\IMatchRefereeEntity;
use Sportal\FootballApi\Domain\Match\IMatchRefereeEntityFactory;
use Sportal\FootballApi\Domain\Match\MatchRefereeRole;
use Sportal\FootballApi\Domain\Person\PersonGender;

class MatchRefereeEntityFactory implements IMatchRefereeEntityFactory
{
    private string $refereeId;

    private ?string $refereeName = null;

    private MatchRefereeRole $role;

    private ?PersonGender $gender = null;

    /**
     * @param string $refereeId
     * @return IMatchRefereeEntityFactory
     */
    public function setRefereeId(string $refereeId): IMatchRefereeEntityFactory
    {
        $this->refereeId = $refereeId;
        return $this;
    }

    /**
     * @param string|null $refereeName
     * @return IMatchRefereeEntityFactory
     */
    public function setRefereeName(?string $refereeName): IMatchRefereeEntityFactory
    {
        $this->refereeName = $refereeName;
        return $this;
    }

    /**
     * @param MatchRefereeRole $role
     * @return IMatchRefereeEntityFactory
     */
    public function setRole(MatchRefereeRole $role): IMatchRefereeEntityFactory
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setGender(?PersonGender $gender): IMatchRefereeEntityFactory
    {
        $this->gender = $gender;
        return $this;
    }

    public function setEmpty(): IMatchRefereeEntityFactory
    {
        return new MatchRefereeEntityFactory();
    }

    public function create(): IMatchRefereeEntity
    {
        return new MatchRefereeEntity($this->refereeId, $this->refereeName, $this->role, $this->gender);
    }

    public function setFrom(IMatchRefereeEntity $refereeEntity): IMatchRefereeEntityFactory
    {
        $factory = new MatchRefereeEntityFactory();
        $factory->refereeId = $refereeEntity->getRefereeId();
        $factory->refereeName = $refereeEntity->getRefereeName();
        $factory->role = $refereeEntity->getRole();
        $factory->gender = $refereeEntity->getGender();
        return $factory;
    }
}