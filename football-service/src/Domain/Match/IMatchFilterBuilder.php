<?php


namespace Sportal\FootballApi\Domain\Match;


interface IMatchFilterBuilder
{

    public function setTournamentIds(?array $tournamentIds): MatchFilterBuilder;

    public function setSeasonIds(?array $seasonIds): MatchFilterBuilder;

    public function setStageIds(?array $stageIds): MatchFilterBuilder;

    public function setGroupIds(?array $groupIds): MatchFilterBuilder;

    public function setRoundIds(?array $roundIds): MatchFilterBuilder;

    public function setFromKickoffTime(?\DateTimeImmutable $fromKickoffTime): MatchFilterBuilder;

    public function setToKickoffTime(?\DateTimeImmutable $toKickoffTime): MatchFilterBuilder;

    public function setTeamIds(?array $teamIds): MatchFilterBuilder;

    public function setStatusTypes(?array $statusTypes): MatchFilterBuilder;

    public function setStatusCodes(?array $statusCodes): MatchFilterBuilder;

    public function setRefereeId(?string $refereeId): MatchFilterBuilder;

    public function setVenueId(?string $venueId): MatchFilterBuilder;

    public function setSortDirection(?string $sortDirection): MatchFilterBuilder;

    public function setTournamentGroup(?string $tournamentGroup): MatchFilterBuilder;

    public function setMatchIds(?array $matchIds): MatchFilterBuilder;

    public function create(): MatchFilter;

    public function setRoundFilter(?array $roundFilter): MatchFilterBuilder;

}