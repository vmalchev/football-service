<?php


namespace Sportal\FootballApi\Infrastructure\Season;

use Sportal\FootballApi\Domain\Season\ISeasonEntity;
use Sportal\FootballApi\Domain\Season\SeasonStatus;
use Sportal\FootballApi\Domain\Tournament\ITournamentEntity;
use Sportal\FootballApi\Infrastructure\Database\GeneratedIdDatabaseEntity;

class SeasonEntity extends GeneratedIdDatabaseEntity implements ISeasonEntity
{
    private ?string $id;

    private string $name;

    private ?ITournamentEntity $tournament;

    private string $tournamentId;

    private SeasonStatus $status;

    /**
     * SeasonEntity constructor.
     * @param string|null $id
     * @param string $name
     * @param ITournamentEntity|null $tournament
     * @param string $tournamentId
     * @param SeasonStatus $status
     */
    public function __construct(?string $id,
                                string $name,
                                ?ITournamentEntity $tournament,
                                string $tournamentId,
                                SeasonStatus $status)
    {
        $this->id = $id;
        $this->name = $name;
        $this->tournament = $tournament;
        $this->tournamentId = $tournamentId;
        $this->status = $status;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getTournamentId(): string
    {
        return $this->tournamentId;
    }

    /**
     * @return ITournamentEntity
     */
    public function getTournament() : ?ITournamentEntity
    {
        return $this->tournament;
    }

    /**
     * @return SeasonStatus
     */
    public function getStatus(): SeasonStatus
    {
        return $this->status;
    }

    public function getDatabaseEntry(): array
    {
        return [
            SeasonTableMapper::FIELD_NAME => $this->name,
            SeasonTableMapper::FIELD_TOURNAMENT_ID => $this->tournamentId,
            SeasonTableMapper::FIELD_ACTIVE => StatusDatabaseConverter::toValue($this->status),
            SeasonTableMapper::FIELD_UPDATED_AT => (new \DateTime())->format(\DateTimeInterface::ATOM)
        ];
    }

    public function withId(string $id): GeneratedIdDatabaseEntity
    {
        $this->id = $id;
        return $this;
    }
}