<?php


namespace Sportal\FootballApi\Domain\Team;


use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Domain\Match\ColorEntityType;
use Sportal\FootballApi\Domain\Match\IMatchRepository;

class TeamColorsRule implements ITeamColorsRule
{

    private IMatchRepository $matchRepository;
    private ITeamRepository $teamRepository;

    /**
     * TeamColorsRule constructor.
     * @param IMatchRepository $matchRepository
     * @param ITeamRepository $teamRepository
     */
    public function __construct(IMatchRepository $matchRepository, ITeamRepository $teamRepository)
    {
        $this->matchRepository = $matchRepository;
        $this->teamRepository = $teamRepository;
    }


    public function validate(string $entityTypeKey, string $entityId): void
    {
        $entityType = ColorEntityType::forKey(strtoupper($entityTypeKey));

        if (ColorEntityType::MATCH()->equals($entityType)) {
            if (is_null($this->matchRepository->findById($entityId))) {
                throw new NoSuchEntityException("Match with id $entityId does not exist.");
            }
        }

        if (ColorEntityType::TEAM()->equals($entityType)) {
            if (is_null($this->teamRepository->findById($entityId))) {
                throw new NoSuchEntityException("Team with id $entityId does not exist.");
            }
        }
    }
}