<?php
namespace Sportal\FootballApi\Dto\Season;

use Sportal\FootballApi\Entity\Season;
use Sportal\FootballApi\Dto\Util;

/**
 * @SWG\Definition(required={"id", "name", "active"})
 */
class SeasonDto implements \JsonSerializable
{

    /**
     *
     * @var int
     * @SWG\Property()
     */
    private $id;

    /**
     *
     * @var string
     * @SWG\Property()
     */
    private $name;

    /**
     *
     * @var boolean
     * @SWG\Property()
     */
    private $active;

    public function __construct($id, $name, $active)
    {
        $this->id = $id;
        $this->name = $name;
        $this->active = $active;
    }

    public function jsonSerialize()
    {
        return Util::filterNull(get_object_vars($this));
    }

    public static function create(Season $season)
    {
        return new SeasonDto($season->getId(), $season->getName(), $season->isActive());
    }
}

