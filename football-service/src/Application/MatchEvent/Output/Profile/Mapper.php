<?php


namespace Sportal\FootballApi\Application\MatchEvent\Output\Profile;

use Sportal\FootballApi\Application\MatchEvent;

class Mapper
{
    private MatchEvent\Output\Get\Mapper $matchEventMapper;

    /**
     * @param MatchEvent\Output\Get\Mapper $matchEventMapper
     */
    public function __construct(MatchEvent\Output\Get\Mapper $matchEventMapper)
    {
        $this->matchEventMapper = $matchEventMapper;
    }

    public function map(string $matchId, array $matchEventEntities): Dto
    {
        $matchEventDtos = [];
        foreach ($matchEventEntities as $matchEventEntity) {
            $matchEventDtos[] = $this->matchEventMapper->map($matchEventEntity);
        }

        return new Dto($matchId, $matchEventDtos);
    }
}