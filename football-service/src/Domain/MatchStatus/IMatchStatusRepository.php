<?php


namespace Sportal\FootballApi\Domain\MatchStatus;


interface IMatchStatusRepository
{
    /**
     * @param string $id
     * @return IMatchStatusEntity|null
     */
    public function findById(string $id): ?IMatchStatusEntity;

    /**
     * @param string[] $types
     * @return IMatchStatusEntity[]|null
     */
    public function findByStatusTypes(array $types): ?array;

    /**
     * @param string[] $codes
     * @return IMatchStatusEntity[]|null
     */
    public function findByStatusCodes(array $codes): ?array;

    /**
     * @return IMatchStatusEntity[]|null
     */
    public function findAll(): ?array;
}