<?php


namespace Sportal\FootballApi\Dto\Statistic\Player;

use Sportal\FootballApi\Dto\IDto;
use Sportal\FootballApi\Model\PartialPerson;
use Sportal\FootballApi\Model\PartialTeam;
use Swagger\Annotations as SWG;

/**
 * Event
 * @SWG\Definition(required={"player", "team", "season", "tournament", "statistics", "position"})
 */
class OutputDto implements IDto, \JsonSerializable
{
    /**
     * @var \Sportal\FootballApi\Model\PartialPerson
     * @SWG\Property()
     */
    public $player;

    /**
     * @var \Sportal\FootballApi\Model\PartialTeam
     * @SWG\Property()
     */
    public $team;

    /**
     * @var \Sportal\FootballApi\Model\TournamentSeason
     * @SWG\Property()
     */
    public $season;

    /**
     * @var \Sportal\FootballApi\Model\Tournament
     * @SWG\Property()
     */
    public $tournament;

    public $statistics;

    public $position;

    /**
     * @param PartialPerson $player
     * @param PartialTeam $team
     * @param $season
     * @param $tournament
     * @param $statistics
     * @param $position
     */
    public function __construct(PartialPerson $player, PartialTeam $team, $season, $tournament, $statistics, $position)
    {
        $this->player = $player;
        $this->team = $team;
        $this->season = $season;
        $this->tournament = $tournament;
        $this->statistics = $statistics;
        $this->position = $position;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
