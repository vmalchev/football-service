<?php


namespace Sportal\FootballApi\Application\Match\Output\Get\Minute;


use JsonSerializable;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_MatchMinute")
 */
class Dto implements JsonSerializable
{
    /**
     * @SWG\Property(property="regular_time")
     * @var int
     */
    private int $regular_time;

    /**
     * @SWG\Property(property="injury_time")
     * @var int|null
     */
    private ?int $injury_time;

    /**
     * MinuteDto constructor.
     * @param int $regular_time
     * @param int|null $injury_time
     */
    public function __construct(int $regular_time, ?int $injury_time)
    {
        $this->regular_time = $regular_time;
        $this->injury_time = $injury_time;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}