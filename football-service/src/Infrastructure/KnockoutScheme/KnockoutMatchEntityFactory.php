<?php


namespace Sportal\FootballApi\Infrastructure\KnockoutScheme;


use Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutMatchEntity;
use Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutMatchEntityFactory;
use Sportal\FootballApi\Domain\Match\IMatchScore;

class KnockoutMatchEntityFactory implements IKnockoutMatchEntityFactory
{

    private ?string $id = null;

    private string $kickoffTime;

    private ?IMatchScore $score = null;

    private ?string $homeTeamId = null;

    private ?string $awayTeamId = null;

    /**
     * @param string|null $id
     * @return IKnockoutMatchEntityFactory
     */
    public function setId(?string $id): IKnockoutMatchEntityFactory
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $kickoffTime
     * @return IKnockoutMatchEntityFactory
     */
    public function setKickoffTime(string $kickoffTime): IKnockoutMatchEntityFactory
    {
        $this->kickoffTime = $kickoffTime;
        return $this;
    }

    /**
     * @param IMatchScore|null $score
     * @return IKnockoutMatchEntityFactory
     */
    public function setScore(?IMatchScore $score): IKnockoutMatchEntityFactory
    {
        $this->score = $score;
        return $this;
    }

    /**
     * @param string|null $homeTeamId
     * @return IKnockoutMatchEntityFactory
     */
    public function setHomeTeamId(?string $homeTeamId): IKnockoutMatchEntityFactory
    {
        $this->homeTeamId = $homeTeamId;
        return $this;
    }

    /**
     * @param string|null $awayTeamId
     * @return IKnockoutMatchEntityFactory
     */
    public function setAwayTeamId(?string $awayTeamId): IKnockoutMatchEntityFactory
    {
        $this->awayTeamId = $awayTeamId;
        return $this;
    }

    public function setEmpty(): IKnockoutMatchEntityFactory
    {
        return new KnockoutMatchEntityFactory();
    }

    public function create(): IKnockoutMatchEntity
    {
        return new KnockoutMatchEntity($this->id,
            $this->kickoffTime,
            $this->score,
            $this->homeTeamId,
            $this->awayTeamId);
    }
}