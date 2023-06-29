<?php


namespace Sportal\FootballApi\Domain\KnockoutScheme;


use Sportal\FootballApi\Domain\Team\ITeamEntity;

interface IKnockoutGroupEntityFactory
{

    /**
     * @param string $id
     * @return IKnockoutGroupEntityFactory
     */
    public function setId(string $id): IKnockoutGroupEntityFactory;

    /**
     * @param int $order
     * @return IKnockoutGroupEntityFactory
     */
    public function setOrder(int $order): IKnockoutGroupEntityFactory;

    /**
     * @param ITeamEntity[] $teams
     * @return IKnockoutGroupEntityFactory
     */
    public function setTeams(array $teams): IKnockoutGroupEntityFactory;

    /**
     * @param IKnockoutMatchEntity[] $matches
     * @return IKnockoutGroupEntityFactory
     */
    public function setMatches(array $matches): IKnockoutGroupEntityFactory;

    /**
     * @param string|null $childObjectId
     * @return IKnockoutGroupEntityFactory
     */
    public function setChildObjectId(?string $childObjectId): IKnockoutGroupEntityFactory;

    public function setEmpty(): IKnockoutGroupEntityFactory;

    public function setFrom(IKnockoutGroupEntity $groupEntity): IKnockoutGroupEntityFactory;

    public function create(): IKnockoutGroupEntity;
}