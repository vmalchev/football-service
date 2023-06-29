<?php

namespace Sportal\FootballApi\Domain\KnockoutScheme;

use Sportal\FootballApi\Domain\Match\IMatchScore;

interface IKnockoutMatchEntityFactory
{
    /**
     * @param string|null $id
     * @return IKnockoutMatchEntityFactory
     */
    public function setId(?string $id): IKnockoutMatchEntityFactory;

    /**
     * @param string $kickoffTime
     * @return IKnockoutMatchEntityFactory
     */
    public function setKickoffTime(string $kickoffTime): IKnockoutMatchEntityFactory;

    /**
     * @param IMatchScore|null $score
     * @return IKnockoutMatchEntityFactory
     */
    public function setScore(?IMatchScore $score): IKnockoutMatchEntityFactory;

    /**
     * @param string|null $homeTeamId
     * @return IKnockoutMatchEntityFactory
     */
    public function setHomeTeamId(?string $homeTeamId): IKnockoutMatchEntityFactory;

    /**
     * @param string|null $awayTeamId
     * @return IKnockoutMatchEntityFactory
     */
    public function setAwayTeamId(?string $awayTeamId): IKnockoutMatchEntityFactory;

    public function setEmpty(): IKnockoutMatchEntityFactory;

    public function create(): IKnockoutMatchEntity;
}