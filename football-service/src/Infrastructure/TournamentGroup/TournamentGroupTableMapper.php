<?php

namespace Sportal\FootballApi\Infrastructure\TournamentGroup;

use Sportal\FootballApi\Domain\TournamentGroup\ITournamentGroupEntity;
use Sportal\FootballApi\Domain\TournamentGroup\ITournamentGroupEntityFactory;
use Sportal\FootballApi\Infrastructure\Database\Mapper\TableMapper;

class TournamentGroupTableMapper implements TableMapper
{

    const TABLE_NAME = 'tournament_group';
    const FIELD_CODE = 'code';
    const FIELD_NAME = 'name';
    const FIELD_DESCRIPTION = 'description';

    private ITournamentGroupEntityFactory $tournamentGroupEntityFactory;

    public function __construct(ITournamentGroupEntityFactory $tournamentGroupEntityFactory)
    {
        $this->tournamentGroupEntityFactory = $tournamentGroupEntityFactory;
    }

    public function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    public function getColumns(): array
    {
        return [
            self::FIELD_CODE,
            self::FIELD_NAME,
            self::FIELD_DESCRIPTION
        ];
    }

    public function toEntity(array $data): ITournamentGroupEntity
    {
        return $this->tournamentGroupEntityFactory
            ->setCode($data['code'])
            ->setName($data['name'])
            ->setDescription($data['description'] ?? null)
            ->create();
    }

    public function getRelations(): ?array
    {
        return [];
    }
}