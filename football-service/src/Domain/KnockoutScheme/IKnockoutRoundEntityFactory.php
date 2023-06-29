<?php


namespace Sportal\FootballApi\Domain\KnockoutScheme;


interface IKnockoutRoundEntityFactory
{

    /**
     * @param string $name
     * @return IKnockoutRoundEntityFactory
     */
    public function setName(string $name): IKnockoutRoundEntityFactory;

    /**
     * @param IKnockoutGroupEntity[] $groups
     * @return IKnockoutRoundEntityFactory
     */
    public function setGroups(array $groups): IKnockoutRoundEntityFactory;

    /**
     * @return IKnockoutRoundEntityFactory
     */
    public function setEmpty(): IKnockoutRoundEntityFactory;

    /**
     * @param IKnockoutRoundEntity $roundEntity
     * @return IKnockoutRoundEntityFactory
     */
    public function setFrom(IKnockoutRoundEntity $roundEntity): IKnockoutRoundEntityFactory;

    /**
     * @return IKnockoutRoundEntity
     */
    public function create(): IKnockoutRoundEntity;
}