<?php


namespace Sportal\FootballApi\Infrastructure\Lineup;


use Sportal\FootballApi\Domain\Coach\ICoachEntity;
use Sportal\FootballApi\Domain\Lineup\ILineupEntity;
use Sportal\FootballApi\Domain\Lineup\ILineupEntityFactory;
use Sportal\FootballApi\Domain\Lineup\LineupStatus;
use Sportal\FootballApi\Infrastructure\Match\MatchTable;

class LineupEntityFactory implements ILineupEntityFactory
{
    private string $matchId;
    private ?LineupStatus $status;
    private ?string $homeTeamFormation;
    private ?string $awayTeamFormation;
    private ?string $homeCoachId;
    private ?string $awayCoachId;
    private ?ICoachEntity $homeCoach = null;
    private ?ICoachEntity $awayCoach = null;
    private ?string $homeTeamId = null;
    private ?string $awayTeamId = null;

    public function setEntity(ILineupEntity $lineup): ILineupEntityFactory
    {
        $factory = new LineupEntityFactory();

        $factory->matchId = $lineup->getMatchId();
        $factory->status = $lineup->getStatus();
        $factory->homeTeamFormation = $lineup->getHomeTeamFormation();
        $factory->awayTeamFormation = $lineup->getAwayTeamFormation();
        $factory->homeCoachId = $lineup->getHomeCoachId();
        $factory->awayCoachId = $lineup->getAwayCoachId();
        $factory->homeTeamId = $lineup->getHomeTeamId();
        $factory->awayTeamId = $lineup->getAwayTeamId();
        $factory->homeCoach = $lineup->getHomeCoach();
        $factory->awayCoach = $lineup->getAwayCoach();
        return $factory;
    }

    public function setEmpty(): ILineupEntityFactory
    {
        return new LineupEntityFactory();
    }

    public function setMatchId(string $matchId): ILineupEntityFactory
    {
        $this->matchId = $matchId;
        return $this;
    }

    public function setStatus(?LineupStatus $status): ILineupEntityFactory
    {
        $this->status = $status;
        return $this;
    }

    public function setHomeCoach(?ICoachEntity $coach): ILineupEntityFactory
    {
        $this->homeCoach = $coach;
        return $this;
    }

    public function setAwayCoach(?ICoachEntity $coach): ILineupEntityFactory
    {
        $this->awayCoach = $coach;
        return $this;
    }

    public function setHomeTeamFormation(?string $formation): ILineupEntityFactory
    {
        $this->homeTeamFormation = $formation;
        return $this;
    }

    public function setHomeCoachId(?string $coachId): ILineupEntityFactory
    {
        $this->homeCoachId = $coachId;
        return $this;
    }

    public function setHomeTeamId(?string $homeTeamId): ILineupEntityFactory
    {
        $this->homeTeamId = $homeTeamId;
        return $this;
    }

    public function setAwayTeamFormation(?string $formation): ILineupEntityFactory
    {
        $this->awayTeamFormation = $formation;
        return $this;
    }

    public function setAwayCoachId(?string $coachId): ILineupEntityFactory
    {
        $this->awayCoachId = $coachId;
        return $this;
    }

    public function setAwayTeamId(?string $awayTeamId): ILineupEntityFactory
    {
        $this->awayTeamId = $awayTeamId;
        return $this;
    }


    public function create(): ILineupEntity
    {
        return new LineupEntity(
            $this->matchId,
            $this->status,
            $this->homeTeamFormation,
            $this->awayTeamFormation,
            $this->homeCoachId,
            $this->awayCoachId,
            $this->homeCoach,
            $this->awayCoach,
            $this->homeTeamId,
            $this->awayTeamId,
        );
    }

    public function createFromArray(array $data): ILineupEntity
    {
        $factory = new LineupEntityFactory();
        $factory->matchId = $data[LineupTable::FIELD_MATCH_ID];
        $factory->status = isset($data[LineupTable::FIELD_CONFIRMED]) ? new LineupStatus($data[LineupTable::FIELD_CONFIRMED]) : null;
        $factory->homeTeamFormation = isset($data[LineupTable::FIELD_HOME_FORMATION]) ? $data[LineupTable::FIELD_HOME_FORMATION] : null;
        $factory->awayTeamFormation = isset($data[LineupTable::FIELD_AWAY_FORMATION]) ? $data[LineupTable::FIELD_AWAY_FORMATION] : null;
        $factory->homeCoachId = isset($data[LineupTable::FIELD_HOME_COACH_ID]) ? $data[LineupTable::FIELD_HOME_COACH_ID] : null;
        $factory->awayCoachId = isset($data[LineupTable::FIELD_AWAY_COACH_ID]) ? $data[LineupTable::FIELD_AWAY_COACH_ID] : null;
        $factory->homeCoach = isset($data[LineupTable::FIELD_HOME_COACH]) ? $data[LineupTable::FIELD_HOME_COACH] : null;
        $factory->awayCoach = isset($data[LineupTable::FIELD_AWAY_COACH]) ? $data[LineupTable::FIELD_AWAY_COACH] : null;
        $factory->homeTeamId = isset($data[MatchTable::TABLE_NAME][LineupTable::FIELD_HOME_TEAM_ID]) ? $data[MatchTable::TABLE_NAME][LineupTable::FIELD_HOME_TEAM_ID] : null;
        $factory->awayTeamId = isset($data[MatchTable::TABLE_NAME][LineupTable::FIELD_AWAY_TEAM_ID]) ? $data[MatchTable::TABLE_NAME][LineupTable::FIELD_AWAY_TEAM_ID] : null;

        return $factory->create();
    }
}