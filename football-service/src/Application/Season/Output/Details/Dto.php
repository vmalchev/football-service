<?php

namespace Sportal\FootballApi\Application\Season\Output\Details;

use Swagger\Annotations as SWG;


/**
 * @SWG\Definition(definition="v2_SeasonDetails")
 */
class Dto implements \JsonSerializable
{

    /**
     * @SWG\Property(property="season")
     * @var \Sportal\FootballApi\Application\Season\Output\Get\Dto
     */
    private \Sportal\FootballApi\Application\Season\Output\Get\Dto $season;

    /**
     * @SWG\Property(property="stages")
     * @var \Sportal\FootballApi\Application\Season\Output\Stage\Dto[]|null
     */
    private ?array $stages;

    /**
     * @param \Sportal\FootballApi\Application\Season\Output\Get\Dto $season
     * @param \Sportal\FootballApi\Application\Season\Output\Stage\Dto[]|null $stages
     */
    public function __construct(\Sportal\FootballApi\Application\Season\Output\Get\Dto $season,
                                ?array $stages)
    {
        $this->season = $season;
        $this->stages = $stages;
    }

    /**
     * @return \Sportal\FootballApi\Application\Season\Output\Get\Dto
     */
    public function getSeason(): \Sportal\FootballApi\Application\Season\Output\Get\Dto
    {
        return $this->season;
    }

    /**
     * @return \Sportal\FootballApi\Application\Season\Output\Stage\Dto[]|null
     */
    public function getStages(): ?array
    {
        return $this->stages;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}