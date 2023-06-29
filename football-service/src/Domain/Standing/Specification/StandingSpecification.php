<?php


namespace Sportal\FootballApi\Domain\Standing\Specification;


use Sportal\FootballApi\Domain\Player\IPlayerRepository;
use Sportal\FootballApi\Domain\Standing\Exception\InvalidStandingException;
use Sportal\FootballApi\Domain\Standing\IStandingEntry;
use Sportal\FootballApi\Domain\Standing\IStandingProfile;
use Sportal\FootballApi\Domain\Standing\StandingEntityName;
use Sportal\FootballApi\Domain\Standing\StandingType;
use Sportal\FootballApi\Domain\Team\ITeamRepository;

class StandingSpecification
{
    private IPlayerRepository $playerRepository;

    private ITeamRepository $teamRepository;

    /**
     * StandingSpecification constructor.
     * @param IPlayerRepository $playerRepository
     * @param ITeamRepository $teamRepository
     */
    public function __construct(IPlayerRepository $playerRepository, ITeamRepository $teamRepository)
    {
        $this->playerRepository = $playerRepository;
        $this->teamRepository = $teamRepository;
    }


    /**
     * @param IStandingProfile $standingProfile
     * @throws InvalidStandingException
     */
    public function validate(IStandingProfile $standingProfile)
    {
        $teamIds = array_map(fn(IStandingEntry $entry) => $entry->getTeamId(), $standingProfile->getEntries());
        $uniqueTeamIds = array_unique($teamIds);

        if ($standingProfile->getStandingEntity()->getType()->equals(StandingType::LEAGUE())) {
            if (count($teamIds) !== count($uniqueTeamIds)) {
                throw new InvalidStandingException("Duplicate teamIds");
            }
            if ($standingProfile->getStandingEntity()->getEntityName()->equals(StandingEntityName::SEASON())) {
                throw new InvalidStandingException("Can't add league standing to season. Stage,group are supported");
            }
        }

        foreach ($uniqueTeamIds as $teamId) {
            if (!$this->teamRepository->exists($teamId)) {
                throw new InvalidStandingException("Team:" . $teamId . " does not exist");
            }
        }

        if ($standingProfile->getStandingEntity()->getType()->equals(StandingType::TOP_SCORER())) {
            $playerIds = array_map(fn(IStandingEntry $entry) => $entry->getPlayerId(), $standingProfile->getEntries());
            $uniquePlayerIds = array_unique($playerIds);
            if (count($playerIds) !== count($uniquePlayerIds)) {
                throw new InvalidStandingException("Duplicate playerIds");
            }
            foreach ($uniquePlayerIds as $playerId) {
                if (!$this->playerRepository->exists($playerId)) {
                    throw new InvalidStandingException("Player:" . $playerId . " does not exist");
                }
            }
        }
    }

}