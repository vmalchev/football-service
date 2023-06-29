<?php

namespace Sportal\FootballApi\Domain\Season;

use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Domain\Tournament\ITournamentRepository;

class SeasonResolver
{

    private ISeasonRepository $seasonRepository;

    private ITournamentRepository $tournamentRepository;

    /**
     * @param ISeasonRepository $seasonRepository
     * @param ITournamentRepository $tournamentRepository
     */
    public function __construct(ISeasonRepository $seasonRepository, ITournamentRepository $tournamentRepository)
    {
        $this->seasonRepository = $seasonRepository;
        $this->tournamentRepository = $tournamentRepository;
    }

    /**
     * @throws NoSuchEntityException
     * @throws \InvalidArgumentException
     */
    public function getSeasonEntity(SeasonFilter $filter): ISeasonEntity
    {
        if (!is_null($filter->getSeasonId())) {
            $seasonEntity = $this->seasonRepository->findById($filter->getSeasonId());

            if (is_null($seasonEntity)) {
                throw new NoSuchEntityException('Resource with identifier ' . $filter->getSeasonId() . ' season_id not found.');
            }
        } elseif (!is_null($filter->getTournamentId())) {
            if ($this->tournamentRepository->exists($filter->getTournamentId()) == false) {
                throw new NoSuchEntityException('Resource with identifier ' . $filter->getTournamentId() . ' tournament_id not found.');
            }

            $seasonEntities = $this->seasonRepository->listByFilter($filter);
            if (!empty($seasonEntities)) {
                $seasonEntity = $seasonEntities[0];
            } else {
                $seasonEntities = $this->seasonRepository->listByFilter($filter->setStatus(SeasonStatus::INACTIVE()));

                if (!empty($seasonEntities)) {
                    $seasonEntity = $seasonEntities[0];
                } else {
                    throw new NoSuchEntityException('No seasons for tournament with identifier '
                        . $filter->getTournamentId() . ' found.');
                }
            }
        } else {
            throw new \InvalidArgumentException("The submitted SeasonFilter has no valid combination of fields.");
        }

        return $seasonEntity;
    }
}