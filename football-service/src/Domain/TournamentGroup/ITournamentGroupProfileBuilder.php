<?php

namespace Sportal\FootballApi\Domain\TournamentGroup;

interface ITournamentGroupProfileBuilder
{

    public function build(ITournamentGroupEntity $tournamentGroupEntity):ITournamentGroupProfile;

}