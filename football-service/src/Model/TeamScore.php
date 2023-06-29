<?php
namespace Sportal\FootballApi\Model;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition()
 */
class TeamScore implements \JsonSerializable
{

    /**
     * Score at the halftime break
     * @var integer
     * @SWG\Property()
     */
    private $half_time;

    /**
     * Score in regular time + injury time
     * @var integer
     * @SWG\Property()
     */
    private $ordinary_time;

    /**
     * Score in extra time, does not include the score for ordinary time
     * @var integer
     * @SWG\Property()
     */
    private $extra_time;

    /**
     * Score in penalty shootout, does not include ordinarytime or extratime
     * @var integer
     * @SWG\Property()
     */
    private $penalty_shootout;

    private function __construct(array $scores)
    {
        foreach ($scores as $key => $value) {
            $this->{$key} = (int) $value;
        }
    }

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this), function ($value) {
            return $value !== null;
        });
    }

    public static function create($scores)
    {
        if (! is_array($scores)) {
            $scores = json_decode($scores, true);
        }
        return new TeamScore($scores);
    }
}