<?php
namespace Sportal\FootballApi\Application\TeamStatistic;

use Sportal\FootballApi\Application\Mapper\LiveCommentaryOutputMapper;
use Sportal\FootballApi\Domain\LiveCommentary\LiveCommentaryCollection;
use Sportal\FootballApi\Domain\TeamStatistic\TeamStatisticAggregator;
use Sportal\FootballApi\Domain\TeamStatistic\TeamStatisticCollection;
use Sportal\FootballApi\Dto\Statistic\Team\OutputDto;
use Sportal\FootballApi\Filter\MatchListingFilter;
use Sportal\FootballApi\Model\StageGroup;
use Sportal\FootballApi\Model\StandingData;
use Sportal\FootballApi\Model\TournamentSeasonStage;
use Sportal\FootballApi\Repository\EventRepository;
use Sportal\FootballApi\Repository\StandingDataRepository;
use Sportal\FootballApi\Repository\TeamRepository;
use Sportal\FootballApi\Repository\TournamentRepository;
use Sportal\FootballApi\Repository\TournamentSeasonRepository;

final class TeamStatisticService implements \Sportal\FootballApi\Application\IService
{
    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * @var TeamRepository
     *
     */
    private $teamRepository;

    /**
     * @var TournamentSeasonRepository
     */
    private $seasonRepository;

    /**
     * @var TournamentRepository
     */
    private $tournamentRepository;

    /**
     * @var StandingDataRepository
     */
    private $standingDataRepository;

    /**
     * @var StandingData
     */
    private $standingData;

    /**
     * @var TeamStatisticAggregator
     */
    private $teamStatisticAggregator;

    public function __construct(
        EventRepository $eventRepository,
        TeamRepository $teamRepository,
        TournamentSeasonRepository $seasonRepository,
        TournamentRepository $tournamentRepository,
        StandingDataRepository $standingDataRepository,
        StandingData $standingData,
        TeamStatisticAggregator $teamStatisticAggregator
    ) {
        $this->eventRepository = $eventRepository;
        $this->teamRepository = $teamRepository;
        $this->seasonRepository = $seasonRepository;
        $this->tournamentRepository = $tournamentRepository;
        $this->standingDataRepository = $standingDataRepository;
        $this->standingData = $standingData;
        $this->teamStatisticAggregator = $teamStatisticAggregator;
    }

    public function process(\Sportal\FootballApi\Application\IDto $dto)
    {
        $teamIds = $dto->teamIds;
        $seasonIds = $dto->seasonIds;

        $output = [];
        foreach ($teamIds as $teamId) {
            $matchListingFilter = new MatchListingFilter(
                [],null,null,null,
                [$teamId],[],$seasonIds
            );

            $matches = $this->eventRepository->getMatches($matchListingFilter);

            $seasonMatches = [];
            $stageMatches = [];

            foreach ($matches as $match) {
                $seasonMatches[$match->getTournamentSeasonStage()->getTournamentSeasonId()][] = $match;
                $stageMatches[$match->getTournamentSeasonStage()->getId()][] = $match;
            }

            $stages = [];
            foreach ($stageMatches as $stageMatchList) {
                $statistic = $this->teamStatisticAggregator->aggregateTeamStatistic($stageMatchList, $teamId);

                if ($stageMatchList[0]->getStageGroup() !== null) {
                    $entityClass = StageGroup::class;
                    $entityId = $stageMatchList[0]->getStageGroup()->getId();
                } else {
                    $entityClass = TournamentSeasonStage::class;
                    $entityId = $stageMatchList[0]->getTournamentSeasonStage()->getId();
                }

                $teamStanding = $this->standingDataRepository->findByEntity($entityClass, $entityId, $teamId);

                $rank = null;
                $points = null;
                if (isset($teamStanding[0])) {
                    $rank = $teamStanding[0]->getRank();
                    $points = $teamStanding[0]->getData()['points'];
                }

                $stages[] = [
                    'stage' => $stageMatchList[0]->getTournamentSeasonStage(),
                    'statistics' => [
                        'goals_conceded' => (int)$statistic->getGoalsConceded(),
                        'goals_scored' => (int)$statistic->getGoalsScored(),
                        'defeats' => (int)$statistic->getDefeats(),
                        'draw' => (int)$statistic->getDraw(),
                        'win' => (int)$statistic->getWin(),
                        'played' => (int)$statistic->getPlayed(),
                        'rank' => $rank,
                        'points' => $points,
                    ],
                ];
            }

            foreach ($seasonMatches as $seasonId => $seasonMatchList) {
                $statistic = $this->teamStatisticAggregator->aggregateTeamStatistic($seasonMatchList, $teamId);
                $tournamentId = $seasonMatches[$seasonId][0]->getTournamentSeasonStage()->getTournamentId();

                $stageOutput = [];
                foreach ($stages as $stage) {
                    if ($stage['stage']->getTournamentSeasonId() == $seasonId) {
                        $stageOutput[] = $stage;
                    }
                }

                $output[] = new OutputDto(
                    $this->teamRepository->find($teamId),
                    $this->seasonRepository->find($seasonId),
                    $this->tournamentRepository->find($tournamentId),
                    $stageOutput,
                    [
                        'goals_conceded' => (int)$statistic->getGoalsConceded(),
                        'goals_scored' => (int)$statistic->getGoalsScored(),
                        'defeats' => (int)$statistic->getDefeats(),
                        'draw' => (int)$statistic->getDraw(),
                        'win' => (int)$statistic->getWin(),
                        'played' => (int)$statistic->getPlayed(),
                    ]
                );
            }
        }

        return $output;
    }
}