<?php


namespace Sportal\FootballApi\Domain\Match;


use Sportal\FootballApi\Domain\Group\IGroupRepository;
use Sportal\FootballApi\Domain\Match\Exception\InvalidMatchException;
use Sportal\FootballApi\Domain\Match\Specification\MatchSpecification;
use Sportal\FootballApi\Domain\MatchStatus\IMatchStatusRepository;
use Sportal\FootballApi\Domain\Round\IRoundRepository;
use Sportal\FootballApi\Domain\Round\RoundType;
use Sportal\FootballApi\Domain\Stage\IStageRepository;
use Sportal\FootballApi\Domain\Stage\StageType;
use Sportal\FootballApi\Domain\Team\ITeamRepository;
use Sportal\FootballApi\Domain\Venue\IVenueRepository;

class MatchModelBuilder implements IMatchModelBuilder
{
    private IMatchStatusRepository $matchStatusRepository;

    private IStageRepository $stageRepository;

    private IGroupRepository $groupRepository;

    private IVenueRepository $venueRepository;

    private MatchRefereeBuilder $matchRefereeBuilder;

    private ITeamRepository $teamRepository;

    private IMatchEntityFactory $matchFactory;

    private IMatchModel $matchModel;

    private MatchSpecification $specification;

    private IRoundRepository $roundRepository;

    /**
     * MatchUpdateBuilder constructor.
     * @param IMatchStatusRepository $matchStatusRepository
     * @param IStageRepository $stageRepository
     * @param IGroupRepository $groupRepository
     * @param IVenueRepository $venueRepository
     * @param MatchRefereeBuilder $matchRefereeBuilder
     * @param ITeamRepository $teamRepository
     * @param IMatchEntityFactory $matchFactory
     * @param IMatchModel $matchModel
     * @param MatchSpecification $specification
     * @param IRoundRepository $roundRepository
     */
    public function __construct(IMatchStatusRepository $matchStatusRepository,
                                IStageRepository $stageRepository,
                                IGroupRepository $groupRepository,
                                IVenueRepository $venueRepository,
                                MatchRefereeBuilder $matchRefereeBuilder,
                                ITeamRepository $teamRepository,
                                IMatchEntityFactory $matchFactory,
                                IMatchModel $matchModel,
                                MatchSpecification $specification,
                                IRoundRepository $roundRepository)
    {
        $this->matchStatusRepository = $matchStatusRepository;
        $this->stageRepository = $stageRepository;
        $this->groupRepository = $groupRepository;
        $this->venueRepository = $venueRepository;
        $this->matchRefereeBuilder = $matchRefereeBuilder;
        $this->teamRepository = $teamRepository;
        $this->matchFactory = $matchFactory;
        $this->matchModel = $matchModel;
        $this->specification = $specification;
        $this->roundRepository = $roundRepository;
    }

    /**
     * @param IMatchEntity $matchEntity
     * @return IMatchModel
     * @throws InvalidMatchException
     */
    public function build(IMatchEntity $matchEntity): IMatchModel
    {
        $group = null;
        if ($matchEntity->getGroupId() !== null) {
            $group = $this->groupRepository->findById($matchEntity->getGroupId());
        }
        $venue = null;
        if ($matchEntity->getVenueId() !== null) {
            $venue = $this->venueRepository->findById($matchEntity->getVenueId());
        }
        $round = null;
        $stage = $this->stageRepository->findById($matchEntity->getStageId());
        if ($matchEntity->getRoundKey() !== null && $stage !== null) {
            $roundType = $stage->getType()->equals(StageType::KNOCK_OUT()) ?
                RoundType::KNOCK_OUT() : RoundType::LEAGUE();

            $round = $this->roundRepository->findByKeyAndType($matchEntity->getRoundKey(), $roundType);
            if ($round === null) {
                throw new InvalidMatchException("Round with key {$matchEntity->getRoundKey()} is not valid");
            }
        }
        $referees = $this->matchRefereeBuilder->build($matchEntity->getReferees());
        $matchEntity = $this->matchFactory->setFrom($matchEntity)
            ->setStatus($this->matchStatusRepository->findById($matchEntity->getStatusId()))
            ->setStage($stage)
            ->setHomeTeam($this->teamRepository->findById($matchEntity->getHomeTeamId()))
            ->setAwayTeam($this->teamRepository->findById($matchEntity->getAwayTeamId()))
            ->setGroup($group)
            ->setVenue($venue)
            ->setReferees($referees)
            ->setRound($round)
            ->setRoundTypeId($round !== null ? $round->getId() : null)
            ->create();
        $this->specification->validate($matchEntity);

        return $this->matchModel->setMatch($matchEntity);
    }

}