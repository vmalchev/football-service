<?php

namespace Sportal\FootballApi\Domain\TournamentGroup;

use Sportal\FootballApi\Domain\Database\ITransactionManager;
use Sportal\FootballApi\Domain\TournamentOrder\ITournamentOrderRepository;

class TournamentGroupModel implements ITournamentGroupModel
{

    private ITournamentGroupRepository $tournamentGroupRepository;

    private ITournamentOrderRepository $tournamentOrderRepository;

    private ITransactionManager $transactionManager;

    private ITournamentGroupEntity $entity;

    private array $tournaments;

    public function __construct(ITournamentGroupRepository $tournamentGroupRepository,
                                ITournamentOrderRepository $tournamentOrderRepository,
                                ITransactionManager $transactionManager)
    {
        $this->tournamentGroupRepository = $tournamentGroupRepository;
        $this->tournamentOrderRepository = $tournamentOrderRepository;
        $this->transactionManager = $transactionManager;
    }

    public function getEntity(): ITournamentGroupEntity
    {
        return $this->entity;
    }

    public function setEntity(ITournamentGroupEntity $entity): TournamentGroupModel
    {
        $model = clone $this;
        $model->entity = $entity;
        return $model;
    }

    public function setTournaments(array $tournaments): TournamentGroupModel
    {
        $model = clone $this;
        $model->tournaments = $tournaments;
        return $model;
    }

    public function insert(): TournamentGroupModel
    {
        return $this->transactionManager->transactional(function () {
            $this->tournamentGroupRepository->insert($this->entity);

            foreach ($this->tournaments as $tournament) {
                $this->tournamentOrderRepository->insert($tournament);
            }

            return $this;
        });
    }

    public function update(): TournamentGroupModel
    {
        return $this->transactionManager->transactional(function () {
            $this->tournamentGroupRepository->update($this->entity);

            $this->tournamentOrderRepository->deleteByClientCode($this->entity->getCode());

            foreach ($this->tournaments as $tournament) {
                $this->tournamentOrderRepository->insert($tournament);
            }

            return $this;
        });
    }

}