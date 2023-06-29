<?php


namespace Sportal\FootballApi\Domain\Lineup;


use Sportal\FootballApi\Domain\Coach\ICoachEntity;

interface ILineupEntityFactory
{
    public function setEntity(ILineupEntity $lineupEntity): ILineupEntityFactory;

    public function setEmpty(): ILineupEntityFactory;

    public function setMatchId(string $matchId): ILineupEntityFactory;

    public function setStatus(?LineupStatus $confirmed): ILineupEntityFactory;

    public function setHomeTeamFormation(?string $formation): ILineupEntityFactory;

    public function setHomeCoachId(?string $coachId): ILineupEntityFactory;

    public function setHomeCoach(?ICoachEntity $coach): ILineupEntityFactory;

    public function setHomeTeamId(?string $homeTeamId): ILineupEntityFactory;

    public function setAwayTeamFormation(?string $formation): ILineupEntityFactory;

    public function setAwayCoachId(?string $coachId): ILineupEntityFactory;

    public function setAwayCoach(?ICoachEntity $coach): ILineupEntityFactory;

    public function setAwayTeamId(?string $homeTeamId): ILineupEntityFactory;

    public function create(): ILineupEntity;

    public function createFromArray(array $data): ILineupEntity;
}