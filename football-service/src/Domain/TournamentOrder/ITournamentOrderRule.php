<?php

namespace Sportal\FootballApi\Domain\TournamentOrder;

interface ITournamentOrderRule
{

    /**
     * @param ITournamentOrderEntity[] $tournamentOrderEntities
     */
    public function validate(array $tournamentOrderEntities);

}