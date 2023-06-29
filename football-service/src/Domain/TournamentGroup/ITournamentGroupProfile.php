<?php

namespace Sportal\FootballApi\Domain\TournamentGroup;


use Sportal\FootballApi\Domain\TournamentOrder\ITournamentOrderEntity;

interface ITournamentGroupProfile
{

    public function getTournamentGroupEntity(): ITournamentGroupEntity;

    public function setTournamentGroupEntity(ITournamentGroupEntity $tournamentGroupEntity): TournamentGroupProfile;

    /**
     * @return ITournamentOrderEntity[]
     */
    public function getTournamentOrderEntities(): array;

    /**
     * @param ITournamentOrderEntity[] $tournamentOrderEntities
     * @return $this
     */
    public function setTournamentOrderEntities(array $tournamentOrderEntities): TournamentGroupProfile;

}