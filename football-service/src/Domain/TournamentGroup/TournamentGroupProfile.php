<?php

namespace Sportal\FootballApi\Domain\TournamentGroup;

use Sportal\FootballApi\Domain\TournamentOrder\ITournamentOrderEntity;

class TournamentGroupProfile implements ITournamentGroupProfile
{

    private ITournamentGroupEntity $tournamentGroupEntity;

    /**
     * @var ITournamentOrderEntity[]
     */
    private array $tournamentOrderEntities;

    public function getTournamentGroupEntity(): ITournamentGroupEntity
    {
        return $this->tournamentGroupEntity;
    }

    /**
     * @inheritDoc
     */
    public function getTournamentOrderEntities(): array
    {
        return $this->tournamentOrderEntities;
    }

    public function setTournamentGroupEntity(ITournamentGroupEntity $tournamentGroupEntity): TournamentGroupProfile
    {
        $profile = clone $this;
        $profile->tournamentGroupEntity = $tournamentGroupEntity;
        return $profile;
    }

    /**
     * @inheritDoc
     */
    public function setTournamentOrderEntities(array $tournamentOrderEntities): TournamentGroupProfile
    {
        $profile = clone $this;
        $profile->tournamentOrderEntities = $tournamentOrderEntities;
        return $profile;
    }

}