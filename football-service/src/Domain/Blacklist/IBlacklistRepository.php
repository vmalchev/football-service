<?php


namespace Sportal\FootballApi\Domain\Blacklist;


interface IBlacklistRepository
{
    /**
     * @param IBlacklistKey[] $blacklistKeys
     * @return IBlacklistEntity[]
     */
    public function insertAll(array $blacklistKeys): array;

    public function exists(IBlacklistKey $key): bool;

    public function delete(string $id): bool;

    public function deleteAll(array $blacklistKeys): void;

    /**
     * @param IBlacklistKey[] $keys
     * @return IBlacklistEntity[]
     */
    public function findByKeys(array $keys): array;

    public function upsert(IBlacklistKey $blacklistKey): void;
}