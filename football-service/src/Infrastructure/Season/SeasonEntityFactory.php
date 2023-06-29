<?php


namespace Sportal\FootballApi\Infrastructure\Season;


use DateTimeInterface;
use Sportal\FootballApi\Domain\Season\ISeasonEntity;
use Sportal\FootballApi\Domain\Season\ISeasonEntityFactory;
use Sportal\FootballApi\Domain\Season\SeasonStatus;
use Sportal\FootballApi\Domain\Tournament\ITournamentEntity;

class SeasonEntityFactory implements ISeasonEntityFactory
{
    private ?string $id = null;

    private string $name;

    private string $tournamentId;

    private ?ITournamentEntity $tournament;

    private SeasonStatus $status;

    public function setFrom(ISeasonEntity $entity): ISeasonEntityFactory
    {
        $factory = new SeasonEntityFactory();
        $factory->id = $entity->getId();
        $factory->name = $entity->getName();
        $factory->status = $entity->getStatus();
        $factory->tournamentId = $entity->getTournamentId();
        $factory->tournament = $entity->getTournament();
        return $factory;
    }

    public function setEmpty(): ISeasonEntityFactory
    {
        return new SeasonEntityFactory();
    }

    /**
     * @param string $id
     * @return SeasonEntityFactory
     */
    public function setId(string $id): ISeasonEntityFactory
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $name
     * @return SeasonEntityFactory
     */
    public function setName(string $name): SeasonEntityFactory
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param SeasonStatus $status
     * @return SeasonEntityFactory
     */
    public function setStatus(SeasonStatus $status): SeasonEntityFactory
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param ?ITournamentEntity $tournament
     * @return SeasonEntityFactory
     */
    public function setTournament(?ITournamentEntity $tournament): SeasonEntityFactory
    {
        $this->tournament = $tournament;
        return $this;
    }

    /**
     * @param string $tournamentId
     * @return SeasonEntityFactory
     */
    public function setTournamentId(string $tournamentId): SeasonEntityFactory
    {
        $this->tournamentId = $tournamentId;
        return $this;
    }

    public function create(): SeasonEntity
    {
        return new SeasonEntity(
            $this->id,
            $this->name,
            $this->tournament,
            $this->tournamentId,
            $this->status
            
        );
    }
}