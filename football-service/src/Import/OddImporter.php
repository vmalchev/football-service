<?php
namespace Sportal\FootballApi\Import;

use Sportal\FootballApi\Repository\OddRepository;
use Sportal\FootballApi\Repository\MappingRepositoryContainer;
use Psr\Log\LoggerInterface;
use Sportal\FootballFeedCommon\OddFeedInterface;
use Sportal\FootballFeedCommon\FeedContainer;
use Sportal\FootballApi\Model\Event;
use Sportal\FootballApi\Util\NameUtil;

class OddImporter
{

    /**
     *
     * @var OddRepository
     */
    protected $repository;

    protected $oddProviderImporter;

    /**
     *
     * @var LoggerInterface
     */
    protected $logger;

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

    public function __construct(OddRepository $repository, MappingRepositoryContainer $mappings, LoggerInterface $logger,
        OddProviderImporter $oddProviderImporter, FeedContainer $feeds)
    {
        $this->repository = $repository;
        $this->mappings = $mappings;
        $this->oddProviderImporter = $oddProviderImporter;
        $this->feeds = $feeds;
        $this->logger = $logger;
    }

    public function importEventOdds(array $eventIds, $sourceName, array $oddProviderIds = null)
    {
        foreach ($eventIds as $eventId) {
            $feedId = $this->mappings->get($sourceName)->getRemoteId('event', $eventId);
            if ($feedId !== null) {
                $data = $this->feeds[$sourceName]->getEventOdds($feedId, $oddProviderIds);
                if (! empty($data)) {
                    $existing = $this->repository->findByEventIndex($eventId, $sourceName);
                    $this->importOdds($existing, $data, $eventId, $sourceName);
                }
            }
        }
    }

    public function importOdds(array $existing, array $odds, $eventId, $sourceName)
    {
        $updated = $created = [];
        foreach ($odds as $oddArr) {
            $provider = $this->oddProviderImporter->importData($oddArr['odd_provider']);
            if ($provider !== null) {
                $model = $this->repository->createObject(
                    [
                        'odd_provider' => $provider,
                        'event_id' => $eventId,
                        'source' => $sourceName,
                        'reference' => isset($oddArr['reference']) ? $oddArr['reference'] : null,
                        'data' => $oddArr['data']
                    ]);
                $key = OddRepository::getKeyName($model);
                if (isset($existing[$key])) {
                    if ($existing[$key]->getData() != $model->getData()) {
                        $existing[$key]->setData($model->getData());
                        $updated[$key] = $model;
                        $this->logger->info(
                            NameUtil::shortClassName(get_class($this)) . ": update " . $eventId . "-" .
                                 $provider->getName());
                    }
                    unset($existing[$key]);
                } else {
                    $created[$key] = $model;
                    $this->logger->info(
                        NameUtil::shortClassName(get_class($this)) . ": create " . $eventId . "-" . $provider->getName());
                }
            }
        }
        $this->repository->flush($created, $updated, $existing);
    }

    public function getMapping($sourceName)
    {
        return $this->mappings->get($sourceName);
    }
}