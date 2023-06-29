<?php


namespace Sportal\FootballApi\Infrastructure\KnockoutScheme;


use Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutMatchEntity;
use Sportal\FootballApi\Domain\Match\IMatchScore;

class KnockoutMatchEntity implements IKnockoutMatchEntity
{

    private ?string $id;

    private string $kickoffTime;

    private ?IMatchScore $score;

    private ?string $homeTeamId;

    private ?string $awayTeamId;

    /**
     * KnockoutMatchEntity constructor.
     * @param string|null $id
     * @param string $kickoffTime
     * @param IMatchScore|null $score
     * @param string|null $homeTeamId
     * @param string|null $awayTeamId
     */
    public function __construct(?string $id, string $kickoffTime, ?IMatchScore $score, ?string $homeTeamId, ?string $awayTeamId)
    {
        $this->id = $id;
        $this->kickoffTime = $kickoffTime;
        $this->score = $score;
        $this->homeTeamId = $homeTeamId;
        $this->awayTeamId = $awayTeamId;
    }


    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getKickoffTime(): string
    {
        return $this->kickoffTime;
    }

    /**
     * @return IMatchScore|null
     */
    public function getScore(): ?IMatchScore
    {
        return $this->score;
    }

    /**
     * @return string|null
     */
    public function getHomeTeamId(): ?string
    {
        return $this->homeTeamId;
    }

    /**
     * @return string|null
     */
    public function getAwayTeamId(): ?string
    {
        return $this->awayTeamId;
    }

    /**
     * @param ?string $id
     * @return KnockoutMatchEntity
     */
    public function setId(?string $id): KnockoutMatchEntity
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string|null $homeTeamId
     * @return KnockoutMatchEntity
     */
    public function setHomeTeamId(?string $homeTeamId): KnockoutMatchEntity
    {
        $this->homeTeamId = $homeTeamId;
        return $this;
    }

    /**
     * @param string|null $awayTeamId
     * @return KnockoutMatchEntity
     */
    public function setAwayTeamId(?string $awayTeamId): KnockoutMatchEntity
    {
        $this->awayTeamId = $awayTeamId;
        return $this;
    }


}