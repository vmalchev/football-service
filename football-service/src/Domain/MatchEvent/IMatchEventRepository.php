<?php

namespace Sportal\FootballApi\Domain\MatchEvent;


use Sportal\FootballApi\Domain\Match\IMatchEntity;

interface IMatchEventRepository
{

    /**
     * @param string $id
     * @return IMatchEventEntity[]
     */
    public function findByMatchId(string $id): array;

    public function deleteByMatch(IMatchEntity $matchEntity): void;

    public function update(IMatchEventEntity $matchEventEntity): void;

    public function insert(IMatchEventEntity $matchEventEntity): IMatchEventEntity;
}