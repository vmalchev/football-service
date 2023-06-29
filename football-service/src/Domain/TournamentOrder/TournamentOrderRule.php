<?php

namespace Sportal\FootballApi\Domain\TournamentOrder;

use Sportal\FootballApi\Application\Exception\DuplicateKeyException;
use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Domain\Tournament\ITournamentRepository;

class TournamentOrderRule implements ITournamentOrderRule
{

    private ITournamentRepository $tournamentRepository;

    public function __construct(ITournamentRepository $tournamentRepository)
    {
        $this->tournamentRepository = $tournamentRepository;
    }

    /**
     * @inheritDoc
     * @throws DuplicateKeyException
     * @throws NoSuchEntityException
     */
    public function validate(array $tournamentOrderEntities)
    {
        $tournamentIds = array_map(
            fn($tournamentOrder) => $tournamentOrder->getTournamentId(), $tournamentOrderEntities
        );
        $tournamentOrders = array_map(
            fn($tournamentOrder) => $tournamentOrder->getSortorder(), $tournamentOrderEntities
        );

        if (array_unique($tournamentIds) !== $tournamentIds) {
            $duplicateIds = array_unique(array_diff_assoc($tournamentIds, array_unique($tournamentIds)));
            throw new DuplicateKeyException('tournament_id ' . implode(',', $duplicateIds));
        } elseif (array_unique($tournamentOrders) !== $tournamentOrders) {
            $duplicateOrders = array_unique(array_diff_assoc($tournamentOrders, array_unique($tournamentOrders)));
            throw new DuplicateKeyException('sort_order ' . implode(',', $duplicateOrders));
        }

        foreach ($tournamentIds as $tournamentId) {
            if (!$this->tournamentRepository->exists($tournamentId)) {
                throw new NoSuchEntityException('Tournament ' . $tournamentId);
            }
        }
    }

}