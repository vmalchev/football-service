<?php

namespace Sportal\FootballApi\Domain\TournamentGroupSelection;

use Sportal\FootballApi\Infrastructure\TournamentGroupSelection\TournamentGroupSelectionRepository;
use Sportal\FootballApi\Infrastructure\TournamentGroupSelection\TournamentGroupSelectionTableMapper;

interface ITournamentGroupSelectionRepository
{

    /**
     * @param ITournamentGroupSelectionEntity $entity
     * @return void
     */
    public function insert(ITournamentGroupSelectionEntity $entity): int;

    /**
     * @param string $code
     * @param \DateTimeInterface $date
     * @return void
     */
    public function deleteByCodeAndDate(string $code, \DateTimeInterface $date): void;

    /**
     * @param string $code
     * @param \DateTimeInterface $date
     * @return ITournamentGroupSelectionEntity[]
     */
    public function findByCodeAndDate(string $code, \DateTimeInterface $date): array;

}