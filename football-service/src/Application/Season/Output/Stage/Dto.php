<?php

namespace Sportal\FootballApi\Application\Season\Output\Stage;


use Swagger\Annotations as SWG;


/**
 * @SWG\Definition(definition="v2_StageDetails")
 */
class Dto implements \JsonSerializable
{

    /**
     * @SWG\Property(property="stage")
     * @var \Sportal\FootballApi\Application\Stage\Output\Get\Dto|null
     */
    private ?\Sportal\FootballApi\Application\Stage\Output\Get\Dto $stage;

    /**
     * @SWG\Property(property="rounds")
     * @var \Sportal\FootballApi\Application\Round\Output\Profile\Dto[]
     */
    private array $rounds;

    /**
     * @param \Sportal\FootballApi\Application\Stage\Output\Get\Dto|null $stage
     * @param \Sportal\FootballApi\Application\Round\Output\Profile\Dto[] $rounds
     */
    public function __construct(\Sportal\FootballApi\Application\Stage\Output\Get\Dto $stage, array $rounds)
    {
        $this->stage = $stage;
        $this->rounds = $rounds;
    }

    /**
     * @return \Sportal\FootballApi\Application\Round\Output\Profile\Dto[]
     */
    public function getRounds(): array
    {
        return $this->rounds;
    }

    /**
     * @return \Sportal\FootballApi\Application\Stage\Output\Get\Dto|null
     */
    public function getStage(): ?\Sportal\FootballApi\Application\Stage\Output\Get\Dto
    {
        return $this->stage;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }

}