<?php

namespace Sportal\FootballApi\Domain\TournamentGroupSelection;

class TournamentGroupSelectionModel implements ITournamentGroupSelectionModel
{

    private ITournamentGroupSelectionRepository $tournamentGroupSelectionRepository;

    private array $entities;

    private string $code;

    private \DateTimeInterface $date;

    public function __construct(ITournamentGroupSelectionRepository $tournamentGroupSelectionRepository)
    {
        $this->tournamentGroupSelectionRepository = $tournamentGroupSelectionRepository;
    }

    public function setEntities(array $entities): TournamentGroupSelectionModel
    {
        $model = clone $this;
        $model->entities = $entities;
        return $model;
    }

    public function setCode(string $code): TournamentGroupSelectionModel
    {
        $model = clone $this;
        $model->code = $code;
        return $model;
    }

    public function setDate(\DateTimeInterface $date): TournamentGroupSelectionModel
    {
        $model = clone $this;
        $model->date = $date;
        return $model;
    }

    public function insert()
    {
        $this->tournamentGroupSelectionRepository->deleteByCodeAndDate($this->code, $this->date);

        foreach ($this->entities as $entity) {
            $this->tournamentGroupSelectionRepository->insert($entity);
        }
    }

}