<?php


namespace Sportal\FootballApi\Infrastructure\Database\Mapper;


use Sportal\FootballApi\Infrastructure\Database\Relation\Relation;

interface TableMapper
{
    public function getTableName(): string;

    /**
     * @return string[]
     */
    public function getColumns(): array;

    public function toEntity(array $data): object;

    /**
     * @return Relation[]
     */
    public function getRelations(): ?array;
}