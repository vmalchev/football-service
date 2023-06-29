<?php
namespace Sportal\FootballApi\Import;

use Sportal\FootballApi\Jobs\JobDispatcherInterface;
use Sportal\FootballApi\Model\Event;
use Sportal\FootballApi\Model\EventPlayer;
use Sportal\FootballApi\Repository\EventPlayerRepository;
use Sportal\FootballApi\Repository\MappingRepositoryContainer;
use Sportal\FootballApi\Repository\PlayerRepository;
use Sportal\FootballApi\Util\NameUtil;
use Sportal\FootballApi\Util\ArrayUtil;
use Psr\Log\LoggerInterface;

class EventPlayerImporter
{

    /**
     *
     * @var EventPlayerRepository
     */
    protected $repository;

    /**
     *
     * @var MappingRepositoryContainer
     */
    protected $mappings;

    /**
     *
     * @var EventPlayerTypeImporter
     */
    protected $typeImporter;

    /**
     *
     * @var PlayerRepository
     */
    protected $playerRepository;

    /**
     *
     * @var JobDispatcherInterface
     */
    protected $dispatcher;

    /**
     *
     * @var LoggerInterface
     */
    protected $logger;

    protected $affected;

    protected $unknownPlayers;

    public function __construct(EventPlayerRepository $repository, MappingRepositoryContainer $mappings,
        EventPlayerTypeImporter $typeImporter, PlayerRepository $playerRepository, JobDispatcherInterface $dispatcher,
        LoggerInterface $logger)
    {
        $this->repository = $repository;
        $this->mappings = $mappings;
        $this->typeImporter = $typeImporter;
        $this->playerRepository = $playerRepository;
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
    }

    public function importPlayers($eventId, array $eventPlayers, $sourceName)
    {
        $this->affected = 0;
        $existing = $this->repository->findByEvent($eventId);
        $existing = ArrayUtil::indexSingle($existing,
            function (EventPlayer $eventPlayer) {
                return $eventPlayer->getUnique();
            });
        $mapping = $this->mappings->get($sourceName);
        $this->unknownPlayers = [];
        
        foreach ($eventPlayers as $data) {
            $data['event_player_type'] = $this->typeImporter->import($data['event_player_type']);
            if (! empty($data['player_id'])) {
                $data['player'] = $this->playerRepository->getPartialFromFeed($data['player_id'], $mapping);
            }
            $data['event_id'] = $eventId;
            $eventPlayer = $this->repository->createObject($data);
            $uniqueKey = $eventPlayer->getUnique();
            if (isset($existing[$uniqueKey])) {
                $this->updateExisting($existing[$uniqueKey], $eventPlayer);
                unset($existing[$uniqueKey]);
            } else {
                $this->repository->create($eventPlayer);
                $this->logger->info(
                    NameUtil::shortClassName(get_class($this)) . " Creating: " . $eventPlayer->getUnique());
                $this->affected ++;
                if ($eventPlayer->getPlayer() === null && ! empty($data['player_id'])) {
                    $this->unknownPlayers[$data['player_id']] = $eventPlayer;
                }
            }
        }
        foreach ($existing as $model) {
            if ($model !== null) {
                $this->logger->info(
                    NameUtil::shortClassName(get_class($this)) . " Deleting: " . $model->getUnique() .
                         ' not found in list');
                $this->repository->delete($model);
                $this->affected ++;
            }
        }
        
        $this->repository->flush();
        $this->dispatchUnknown($sourceName);
        return $this->affected;
    }

    public function import($eventId)
    {
        $feedId = $this->mapping->getRemoteId(Event::class, $eventId);
        if ($feedId !== null) {
            $eventPlayers = $this->feed->getEventPlayers($feedId);
            if (! empty($eventPlayers)) {
                $this->importPlayers($eventId, $eventPlayers);
            }
        }
    }

    protected function updateExisting(EventPlayer $existing, EventPlayer $updated)
    {
        $changes = $this->repository->getChanges($existing, $updated);
        if (! empty($changes)) {
            $updated = $this->repository->patchExisting($existing, $updated);
            $this->repository->update($existing, $updated);
            $this->logger->info(
                NameUtil::shortClassName(get_class($this)) . " Updating: " . $updated->getUnique() . " " .
                     implode('-', $changes));
            $this->affected ++;
            return $updated;
        }
        return $existing;
    }

    protected function dispatchUnknown($sourceName)
    {
        foreach ($this->unknownPlayers as $id => $eventPlayer) {
            $this->dispatcher->dispatch('Import\UnknownEventPlayer',
                [
                    $id,
                    $eventPlayer->getId(),
                    $sourceName
                ]);
        }
    }
}