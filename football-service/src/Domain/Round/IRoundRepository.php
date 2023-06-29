<?php

namespace Sportal\FootballApi\Domain\Round;


interface IRoundRepository
{

    public function findByKeyAndType(string $key, RoundType $type): ?IRoundEntity;

    /**
     * @return IRoundEntity[]
     */
    public function findAll(RoundFilter $filter): array;

    public function insert(IRoundEntity $roundEntity): IRoundEntity;

    public function exists(string $id): bool;

}