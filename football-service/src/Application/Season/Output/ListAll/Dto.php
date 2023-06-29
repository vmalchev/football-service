<?php


namespace Sportal\FootballApi\Application\Season\Output\ListAll;


use JsonSerializable;
use Sportal\FootballApi\Application\IDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_SeasonList", required={"seasons"})
 */
class Dto implements JsonSerializable, IDto
{

    /**
     * @var \Sportal\FootballApi\Application\Season\Output\Get\Dto[]
     * @SWG\Property(property="seasons")
     */
    private array $seasons;

    /**
     * @param \Sportal\FootballApi\Application\Season\Output\Get\Dto[] $seasons
     */
    public function __construct(array $seasons)
    {
        $this->seasons = $seasons;
    }

    /**
     * @return \Sportal\FootballApi\Application\Season\Output\Get\Dto[]
     */
    public function getSeasons(): array
    {
        return $this->seasons;
    }


    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}