<?php

namespace Sportal\FootballApi\Infrastructure\TournamentOrder;

use Closure;
use Sportal\FootballApi\Domain\TournamentOrder\ITournamentOrderEntity;
use Sportal\FootballApi\Domain\TournamentOrder\ITournamentOrderEntityFactory;
use Sportal\FootballApi\Infrastructure\Database\Mapper\TableMapper;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationFactory;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationType;
use Sportal\FootballApi\Infrastructure\Tournament\TournamentTableMapper;
use Sportal\FootballApi\Infrastructure\TournamentGroup\TournamentGroupTableMapper;

class TournamentOrderTableMapper implements TableMapper
{

    const TABLE_NAME = 'tournament_order';
    const FIELD_CLIENT_CODE = 'client_code';
    const FIELD_TOURNAMENT_ID = 'tournament_id';
    const FIELD_SORTORDER = 'sortorder';

    private ITournamentOrderEntityFactory $tournamentOrderEntityFactory;

    private RelationFactory $relationFactory;

    public function __construct(ITournamentOrderEntityFactory $tournamentOrderEntityFactory,
                                RelationFactory $relationFactory)
    {
        $this->tournamentOrderEntityFactory = $tournamentOrderEntityFactory;
        $this->relationFactory = $relationFactory;
    }

    public function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    public function toEntity(array $data): ITournamentOrderEntity
    {
        return $this->tournamentOrderEntityFactory
            ->setClientCode($data['client_code'])
            ->setTournamentId($data['tournament_id'])
            ->setSortorder($data['sortorder'])
            ->setTournamentEntity(array_key_exists('tournament', $data) ? $data['tournament'] : null)
            ->create();
    }

    public function getColumns(): array
    {
        return [
            self::FIELD_CLIENT_CODE,
            self::FIELD_TOURNAMENT_ID,
            self::FIELD_SORTORDER
        ];
    }

    public function getRelations(): ?array
    {
        return [
            $this->relationFactory
                ->from(TournamentTableMapper::TABLE_NAME, RelationType::OPTIONAL())
                ->create()
        ];
    }
}