<?php

namespace Sportal\FootballApi\Import;

use Psr\Log\LoggerInterface;
use Sportal\FootballApi\Model\Event;
use Sportal\FootballApi\Model\OddProvider;
use Sportal\FootballApi\Repository\LiveOddRepository;
use Sportal\FootballApi\Repository\MappingRepositoryContainer;
use Sportal\FootballApi\Repository\OddLinkRepository;
use Sportal\FootballApi\Util\NameUtil;
use Sportal\FootballFeedCommon\FeedContainer;
use Sportal\FootballFeedCommon\OddFeedInterface;

class LiveOddImporter
{

    /**
     *
     * @var LiveOddRepository
     */
    protected $repository;

    protected $oddProviderImporter;

    /**
     *
     * @var MappingRepositoryContainer
     */
    protected $mappings;

    /**
     *
     * @var OddFeedInterface
     */
    protected $feeds;

    protected $logger;

    private $oddLinkRepository;

    public function __construct(LiveOddRepository $repository, MappingRepositoryContainer $mappings,
                                LoggerInterface $logger, OddProviderImporter $oddProviderImporter, FeedContainer $feeds, OddLinkRepository $oddLinkRepository)
    {
        $this->repository = $repository;
        $this->mappings = $mappings;
        $this->oddProviderImporter = $oddProviderImporter;
        $this->feeds = $feeds;
        $this->logger = $logger;
        $this->oddLinkRepository = $oddLinkRepository;
    }

    public function importLiveOddsByTournament($sourceName)
    {
        $this->logger->info(NameUtil::shortClassName(get_class($this) . ": Starting live odd import . " . date(\DateTime::RFC822)));
        $oddProrviderIds = [];
        $links = $this->oddLinkRepository->findAll();
        foreach ($links as $link) {
            $oddProrviderIds[] = $this->mappings->get('enetpulse')->getRemoteId(OddProvider::class,
                $link->getOddProviderId());
        }
        $liveodds = $this->feeds[$sourceName]->getLiveOdds(array_unique($oddProrviderIds));
        $this->import($liveodds, $sourceName);
        $this->logger->info(
            NameUtil::shortClassName(get_class($this) . ": Finished live odd import . " . date(\DateTime::RFC822)));
    }

    private function import(array $liveodds, $sourceName)
    {
        $eventLookup = [];
        $models = [];
        foreach ($liveodds as $oddArr) {
            $feedEventId = $oddArr['event']['id'];
            if (!isset($eventLookup[$feedEventId])) {
                $eventLookup[$feedEventId] = $this->mappings->get($sourceName)->getOwnId(Event::class, $feedEventId);
            }
            $eventId = $eventLookup[$feedEventId];
            $provider = $this->oddProviderImporter->importData($oddArr['odd_provider']);
            if ($provider !== null && !empty($eventId)) {
                $models[] = $this->repository->createObject(
                    [
                        'odd_provider' => $provider,
                        'event_id' => $eventId,
                        'source' => $sourceName,
                        'reference' => isset($oddArr['reference']) ? $oddArr['reference'] : null,
                        'data' => $oddArr['data']
                    ]);
            }
        }

        $this->repository->flush($models);
    }
}