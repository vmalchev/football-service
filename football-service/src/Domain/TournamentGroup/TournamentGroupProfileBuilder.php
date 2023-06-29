<?php

namespace Sportal\FootballApi\Domain\TournamentGroup;

use Sportal\FootballApi\Domain\TournamentOrder\ITournamentOrderRepository;

class TournamentGroupProfileBuilder implements ITournamentGroupProfileBuilder
{

    private ITournamentOrderRepository $tournamentOrderRepository;

    private ITournamentGroupProfile $tournamentGroupProfile;

    public function __construct(ITournamentOrderRepository $tournamentOrderRepository,
                                ITournamentGroupProfile $tournamentGroupProfile)
    {
        $this->tournamentOrderRepository = $tournamentOrderRepository;
        $this->tournamentGroupProfile = $tournamentGroupProfile;
    }

    public function build(ITournamentGroupEntity $tournamentGroupEntity): ITournamentGroupProfile
    {
        $tournamentOrderEntities = $this->tournamentOrderRepository->findByClientCode($tournamentGroupEntity->getCode());

        return $this->tournamentGroupProfile
            ->setTournamentGroupEntity($tournamentGroupEntity)
            ->setTournamentOrderEntities($tournamentOrderEntities);
    }

}