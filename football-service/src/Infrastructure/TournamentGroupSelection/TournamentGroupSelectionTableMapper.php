<?php

namespace Sportal\FootballApi\Infrastructure\TournamentGroupSelection;

use Sportal\FootballApi\Domain\TournamentGroupSelection\ITournamentGroupSelectionEntity;
use Sportal\FootballApi\Domain\TournamentGroupSelection\ITournamentGroupSelectionEntityFactory;

class TournamentGroupSelectionTableMapper
{

    const TABLE_NAME = 'tournament_group_selection';
    const FIELD_CODE = 'code';
    const FIELD_DATE = 'date';
    const FIELD_MATCH_ID = 'match_id';

    private ITournamentGroupSelectionEntityFactory $entityFactory;

    public function __construct(ITournamentGroupSelectionEntityFactory $entityFactory)
    {
        $this->entityFactory = $entityFactory;
    }

    public function toEntity(array $data): ITournamentGroupSelectionEntity
    {
        return $this->entityFactory
            ->setCode($data['code'])
            ->setDate(\DateTime::createFromFormat('Y-m-d', $data['date']))
            ->setMatchId($data['match_id'])
            ->create();
    }

}