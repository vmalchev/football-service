<?php

namespace Sportal\FootballApi\Dto\Statistic\Team;


use JsonSerializable;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Model\Team;
use Sportal\FootballApi\Model\Tournament;
use Sportal\FootballApi\Model\TournamentSeason;
use Sportal\FootballApi\Model\Translateable;

class OutputDto implements IDto, Translateable, JsonSerializable
{
    /**
     * @var Team
     */
    public $team;

    /**
     * @var TournamentSeason
     */
    public $season;

    /**
     * @var Tournament
     */
    public $tournament;

    public $stages;

    public $statistics;

    /**
     * @param Team $team
     * @param TournamentSeason $season
     * @param Tournament $tournament
     * @param $stages
     * @param $statistics
     */
    public function __construct(Team $team, TournamentSeason $season, Tournament $tournament, $stages, array $statistics)
    {
        $this->team = $team;
        $this->season = $season;
        $this->tournament = $tournament;
        $this->stages = $stages;
        $this->statistics = $statistics;
    }

    public function getMlContentModels()
    {
        $allModels = [];
        foreach ($this->stages as $stage) {
            $allModels = array_merge($allModels, $stage['stage']->getMlContentModels());
        }

        if (!is_null($this->season)) {
            $allModels = array_merge($allModels, $this->season->getMlContentModels());
        }

        return array_merge(
            $allModels,
            $this->team->getMlContentModels(),
            $this->tournament->getMlContentModels()
        );
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
