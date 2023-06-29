<?php


namespace Sportal\FootballApi\Application\MatchEvent\Output\Profile;


use JsonSerializable;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IEventNotificationable;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_MatchEventProfile")
 */
class Dto implements JsonSerializable, IDto, IEventNotificationable
{

    private string $matchId;

    /**
     * @var \Sportal\FootballApi\Application\MatchEvent\Output\Get\Dto[]
     * @SWG\Property(property="events")
     */
    private array $events;

    /**
     * @param \Sportal\FootballApi\Application\MatchEvent\Output\Get\Dto[] $events
     */
    public function __construct($matchId, array $events)
    {
        $this->matchId = $matchId;
        $this->events = $events;
    }

    /**
     * @return \Sportal\FootballApi\Application\MatchEvent\Output\Get\Dto[]
     */
    public function getEvents(): array
    {
        return $this->events;
    }


    public function jsonSerialize(): array
    {
        return ["events" => $this->events];
    }

    public function getId(): string
    {
        return $this->matchId;
    }
}