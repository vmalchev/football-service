<?php

namespace Sportal\FootballApi\Application\TournamentGroup\Output\Get;

use Sportal\FootballApi\Application\IDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_TournamentItemOutput")
 */
class TournamentItemDto implements IDto, \JsonSerializable
{

    /**
     * @var \Sportal\FootballApi\Application\Tournament\Output\Get\Dto
     * @SWG\Property(property="tournament")
     */
    private \Sportal\FootballApi\Application\Tournament\Output\Get\Dto $tournament;

    /**
     * @var int
     * @SWG\Property(property="sort_order")
     */
    private int $sort_order;

    public function __construct(\Sportal\FootballApi\Application\Tournament\Output\Get\Dto $tournament,
                                int $sort_order)
    {
        $this->tournament = $tournament;
        $this->sort_order = $sort_order;
    }

    public function getTournament(): \Sportal\FootballApi\Application\Tournament\Output\Get\Dto
    {
        return $this->tournament;
    }

    public function getSortOrder(): int
    {
        return $this->sort_order;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}