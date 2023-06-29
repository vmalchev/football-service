<?php


namespace Sportal\FootballApi\Domain\Match;


use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\Match\Input\ListAll\RoundFilterDto;
use Sportal\FootballApi\Domain\Group\IGroupRepository;
use Sportal\FootballApi\Domain\MatchStatus\IMatchStatusRepository;
use Sportal\FootballApi\Domain\Referee\IRefereeRepository;
use Sportal\FootballApi\Domain\Round\IRoundRepository;
use Sportal\FootballApi\Domain\Season\ISeasonRepository;
use Sportal\FootballApi\Domain\Stage\IStageRepository;
use Sportal\FootballApi\Domain\Team\ITeamRepository;
use Sportal\FootballApi\Domain\Tournament\ITournamentRepository;
use Sportal\FootballApi\Domain\Venue\IVenueRepository;

class MatchFilterValidator
{

    private ITournamentRepository $tournamentRepository;

    private ISeasonRepository $seasonRepository;

    private IStageRepository $stageRepository;

    private IGroupRepository $groupRepository;

    private ITeamRepository $teamRepository;

    private IMatchStatusRepository $matchStatusRepository;

    private IRefereeRepository $refereeRepository;

    private IVenueRepository $venueRepository;

    private IRoundRepository $roundRepository;

    /**
     * MatchFilterValidator constructor.
     * @param ITournamentRepository $tournamentRepository
     * @param ISeasonRepository $seasonRepository
     * @param IStageRepository $stageRepository
     * @param IGroupRepository $groupRepository
     * @param ITeamRepository $teamRepository
     * @param IMatchStatusRepository $matchStatusRepository
     * @param IRefereeRepository $refereeRepository
     * @param IVenueRepository $venueRepository
     * @param IRoundRepository $roundRepository
     */
    public function __construct(
        ITournamentRepository  $tournamentRepository,
        ISeasonRepository      $seasonRepository,
        IStageRepository       $stageRepository,
        IGroupRepository       $groupRepository,
        ITeamRepository        $teamRepository,
        IMatchStatusRepository $matchStatusRepository,
        IRefereeRepository     $refereeRepository,
        IVenueRepository       $venueRepository,
        IRoundRepository       $roundRepository
    )
    {
        $this->tournamentRepository = $tournamentRepository;
        $this->seasonRepository = $seasonRepository;
        $this->stageRepository = $stageRepository;
        $this->groupRepository = $groupRepository;
        $this->teamRepository = $teamRepository;
        $this->matchStatusRepository = $matchStatusRepository;
        $this->refereeRepository = $refereeRepository;
        $this->venueRepository = $venueRepository;
        $this->roundRepository = $roundRepository;
    }

    /**
     * @param MatchFilter $filter
     * @throws NoSuchEntityException
     */
    public function validate(MatchFilter $filter): void
    {
        $this->validateTournaments($filter);

        $this->validateSeasons($filter);

        $this->validateStages($filter);

        $this->validateRounds($filter);

        $this->validateGroups($filter);

        $this->validateTeams($filter);

        $this->validateStatusCodes($filter);

        $this->validateReferee($filter);

        $this->validateVenue($filter);
    }

    /**
     * @param MatchFilter $filter
     * @throws NoSuchEntityException
     */
    private function validateTournaments(MatchFilter $filter)
    {
        if (empty($filter->getTournamentIds())) {
            return;
        }

        foreach ($filter->getTournamentIds() as $tournamentId) {
            if (!$this->tournamentRepository->exists($tournamentId)) {
                throw new NoSuchEntityException("Tournament with id " . $tournamentId . " does not exist.");
            }
        }
    }

    /**
     * @param MatchFilter $filter
     * @throws NoSuchEntityException
     */
    private function validateSeasons(MatchFilter $filter)
    {
        if (empty($filter->getSeasonIds())) {
            return;
        }

        foreach ($filter->getSeasonIds() as $seasonId) {
            if (!$this->seasonRepository->exists($seasonId)) {
                throw new NoSuchEntityException("Season with id " . $seasonId . " does not exist.");
            }
        }
    }

    /**
     * @param MatchFilter $filter
     * @throws NoSuchEntityException
     */
    private function validateStages(MatchFilter $filter)
    {
        if (empty($filter->getStageIds()) && empty($filter->getRoundFilter())) {
            return;
        }

        $stageIds = empty($filter->getStageIds())
            ? array_map(fn($roundFilter) => $roundFilter->getStageId(), $filter->getRoundFilter())
            : $filter->getStageIds();

        foreach ($stageIds as $stageId) {
            if (!$this->stageRepository->exists($stageId)) {
                throw new NoSuchEntityException("Stage with id " . $stageId . " does not exist.");
            }
        }
    }

    /**
     * @param MatchFilter $filter
     * @throws NoSuchEntityException
     */
    private function validateGroups(MatchFilter $filter)
    {
        if (empty($filter->getGroupIds())) {
            return;
        }

        foreach ($filter->getGroupIds() as $groupId) {
            if (!$this->groupRepository->exists($groupId)) {
                throw new NoSuchEntityException("Group with id " . $groupId . " does not exist.");
            }
        }
    }

    /**
     * @param MatchFilter $filter
     * @throws NoSuchEntityException
     */
    private function validateTeams(MatchFilter $filter)
    {
        if (empty($filter->getTeamIds())) {
            return;
        }

        foreach ($filter->getTeamIds() as $teamId) {
            if (!$this->teamRepository->exists($teamId)) {
                throw new NoSuchEntityException("Team with id " . $teamId . " does not exist.");
            }
        }
    }

    /**
     * @param MatchFilter $filter
     * @throws NoSuchEntityException
     */
    private function validateStatusCodes(MatchFilter $filter)
    {
        if (empty($filter->getStatusCodes())) {
            return;
        }

        $validStatusCodes = array_map(fn($status) => $status->getCode(),
            $this->matchStatusRepository->findAll());

        foreach ($filter->getStatusCodes() as $statusCode) {
            if (!in_array($statusCode, $validStatusCodes)) {
                throw new NoSuchEntityException("Invalid status code, select one in " . implode(',', $validStatusCodes));
            }
        }
    }

    /**
     * @param MatchFilter $filter
     * @throws NoSuchEntityException
     */
    private function validateReferee(MatchFilter $filter)
    {
        if (is_null($filter->getRefereeId())) {
            return;
        }

        if (!$this->refereeRepository->exists($filter->getRefereeId())) {
            throw new NoSuchEntityException("No referee with id " . $filter->getRefereeId() . " exists.");
        }
    }

    /**
     * @param MatchFilter $filter
     * @throws NoSuchEntityException
     */
    private function validateVenue(MatchFilter $filter)
    {
        if (is_null($filter->getVenueId())) {
            return;
        }

        if (!$this->venueRepository->exists($filter->getVenueId())) {
            throw new NoSuchEntityException("No venue with id " . $filter->getVenueId() . " exists.");
        }
    }

    /**
     * @param MatchFilter $filter
     * @throws NoSuchEntityException
     */
    private function validateRounds(MatchFilter $filter)
    {
        if (empty($filter->getRoundIds()) && empty($filter->getRoundFilter())) {
            return;
        }

        $roundIds = empty($filter->getRoundIds())
            ? array_map(fn($roundFilter) => $roundFilter->getRoundId(), $filter->getRoundFilter())
            : $filter->getRoundIds();

        foreach ($roundIds as $roundId) {
            if (!$this->roundRepository->exists($roundId)) {
                throw new NoSuchEntityException("Round with id " . $roundId . " does not exist.");
            }
        }
    }
}