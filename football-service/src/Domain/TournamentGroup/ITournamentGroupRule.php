<?php

namespace Sportal\FootballApi\Domain\TournamentGroup;


interface ITournamentGroupRule
{

    /**
     * @param ITournamentGroupEntity $tournamentGroup
     * @param bool $insert
     */
    public function validate(ITournamentGroupEntity $tournamentGroup, bool $insert);

}