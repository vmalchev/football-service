<?php


namespace Sportal\FootballApi\Infrastructure\StageTeam;


use Sportal\FootballApi\Infrastructure\Database\GeneratedIdDatabaseEntity;
use Sportal\FootballApi\Infrastructure\Database\IDatabaseEntity;
use Sportal\FootballApi\Infrastructure\Season\TournamentSeasonTeamTableMapper;

class SeasonTeamEntity extends GeneratedIdDatabaseEntity implements IDatabaseEntity
{

    private ?string $id;

    private string $tournamentSeasonId;

    private string $teamId;

    public function __construct(?string $id, string $tournamentSeasonId, string $teamId)
    {
        $this->id = $id;
        $this->tournamentSeasonId = $tournamentSeasonId;
        $this->teamId = $teamId;
    }

    public function getTeamId(): string
    {
        return $this->teamId;
    }

    public function getTournamentSeasonId(): string
    {
        return $this->tournamentSeasonId;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getDatabaseEntry(): array
    {
        return [
            TournamentSeasonTeamTableMapper::FIELD_TOURNAMENT_SEASON_ID => $this->getTournamentSeasonId(),
            TournamentSeasonTeamTableMapper::FIELD_TEAM_ID => $this->getTeamId()
        ];
    }

    public function getPrimaryKey(): array
    {
        return [
            TournamentSeasonTeamTableMapper::FIELD_ID => $this->getId()
        ];
    }

    public function withId(string $id): GeneratedIdDatabaseEntity
    {
        $this->id = $id;
        return $this;
    }
}