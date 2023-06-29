<?php
namespace Sportal\FootballApi\Matcher;

use Sportal\FootballApi\Repository\EventRepository;
use Sportal\FootballApi\Repository\MappingRepositoryContainer;
use Sportal\FootballApi\Model\Team;
use Sportal\FootballFeedCommon\EventFeedInterface;
use Sportal\FootballFeedCommon\FeedContainer;
use Psr\Log\LoggerInterface;
use Sportal\FootballApi\Model\TournamentSeasonStage;
use Sportal\FootballApi\Repository\TournamentSeasonStageRepository;

class EventMatcher
{

    private $eventRepository;

    private $mappings;

    /**
     *
     * @var EventFeedInterface
     */
    private $feeds;

    private $log;

    private $stageRepository;

    public function __construct(EventRepository $eventRepository, MappingRepositoryContainer $mappings,
        FeedContainer $feeds, LoggerInterface $log, TournamentSeasonStageRepository $stageRepository)
    {
        $this->eventRepository = $eventRepository;
        $this->mappings = $mappings;
        $this->feeds = $feeds;
        $this->log = $log;
        $this->stageRepository = $stageRepository;
    }

    public function findMatch(array $remoteData, $source)
    {
        $teams = [];
        $mapping = $this->mappings->get($source);
        $teams['home_id'] = $mapping->getOwnId(Team::class, $remoteData['home_id']);
        $teams['away_id'] = $mapping->getOwnId(Team::class, $remoteData['away_id']);
        $stageId = $mapping->getOwnId(TournamentSeasonStage::class, $remoteData['tournament_season_stage_id']);
        
        $notFound = array_filter($teams, function ($value) {
            return empty($value);
        });
        
        if (! empty($notFound)) {
            throw new UnknownTeamException($source,
                [
                    $remoteData['home_id'],
                    $remoteData['away_id']
                ]);
        }
        
        $events = $this->eventRepository->findMatchingByTime($teams['home_id'], $teams['away_id'],
            new \DateTime($remoteData['start_time']));
        
        if (count($events) == 1) {
            $mapping->setMapping($this->eventRepository->getModelClass(), $remoteData['id'], $events[0]->getId());
            return $events[0];
        } elseif (! empty($stageId) && ! empty($remoteData['round'])) {
            $this->log->info('Attempting Stage match for ' . $this->getIdentifier($remoteData));
            $events = $this->eventRepository->findMatchingByStage($teams['home_id'], $teams['away_id'], $stageId,
                $remoteData['round']);
            if (count($events) == 1) {
                $mapping->setMapping($this->eventRepository->getModelClass(), $remoteData['id'], $events[0]->getId());
                return $events[0];
            }
        }
        
        return null;
    }

    public function match($stageId, $source)
    {
        $feedStageId = $this->mappings->get($source)->getRemoteId(TournamentSeasonStage::class, $stageId);
        if ($feedStageId !== null) {
            $this->matchStage($stageId, $feedStageId, $source);
        }
    }

    public function matchAll($source)
    {
        $remoteIds = $this->mappings->get($source)->getRemoteIds(TournamentSeasonStage::class);
        foreach ($remoteIds as $feedStageId) {
            $ownId = $this->mappings->get($source)->getOwnId(TournamentSeasonStage::class, $feedStageId);
            if ($ownId !== null) {
                $this->matchStage($ownId, $feedStageId, $source);
            }
        }
    }

    private function matchStage($ownId, $feedStageId, $source)
    {
        $ownEventCount = $this->eventRepository->getEventCount($ownId);
        if ($ownEventCount > 0) {
            $totalMatch = 0;
            $remoteEvents = $this->feeds[$source]->getTournamentStageEvents($feedStageId);
            foreach ($remoteEvents as $remoteData) {
                try {
                    $matched = $this->findMatch($remoteData, $source);
                    if ($matched !== null) {
                        $totalMatch ++;
                        $this->log->info('Matched ' . $matched->getName() . ' to ' . $this->getIdentifier($remoteData));
                    } else {
                        $this->log->info('Failed matching for ' . $this->getIdentifier($remoteData));
                    }
                } catch (UnknownTeamException $e) {
                    $this->log->info('Could not find both teams for ' . $this->getIdentifier($remoteData));
                }
            }
            $stage = $this->stageRepository->find($ownId);
            $this->log->info(
                "Done: " . $stage->getId() . " " . $stage->getName() . " " . $stage->getCountry()
                    ->getName() . " $totalMatch / $ownEventCount");
        }
    }

    private function getIdentifier(array $remoteData)
    {
        return $remoteData['home_name'] . '-' . $remoteData['away_name'] . " (" . $remoteData['start_time'] . ")";
    }
}