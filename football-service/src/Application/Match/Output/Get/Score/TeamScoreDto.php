<?php


namespace Sportal\FootballApi\Application\Match\Output\Get\Score;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_TeamScore")
 */
class TeamScoreDto implements \JsonSerializable
{
    /**
     * @SWG\Property(property="home")
     * @var int
     */
    private int $home;

    /**
     * @SWG\Property(property="away")
     * @var int
     */
    private int $away;

    /**
     * TeamScoreDto constructor.
     * @param int $home
     * @param int $away
     */
    public function __construct(int $home, int $away)
    {
        $this->home = $home;
        $this->away = $away;
    }

    /**
     * @return int
     */
    public function getHome(): int
    {
        return $this->home;
    }

    /**
     * @return int
     */
    public function getAway(): int
    {
        return $this->away;
    }



    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}