<?php
namespace Sportal\FootballApi\Import;

use Sportal\FootballApi\Repository\MappingRepositoryInterface;
use Sportal\FootballApi\Repository\EventPlayerRepository;
use Sportal\FootballApi\Model\EventPlayer;
use Sportal\FootballApi\Util\NameUtil;
use Sportal\FootballApi\Model\Player;
use Sportal\FootballApi\Model\Event;
use Sportal\FootballFeedCommon\EventPlayerStatsFeedInterface;
use Psr\Log\LoggerInterface;

/**
 * @author kstoilov
 *
 */
class EventPlayerStatsImporter extends MappableImporter
{

    /**
     *
     * @var EventPlayerRepository
     */
    protected $repository;

    /**
     *
     * @var EventPlayerRepository
     */
    protected $eventPlayerRepository;

    protected $feed;

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Import\MappableImporter::__construct()
     */
    public function __construct(EventPlayerRepository $repository, MappingRepositoryInterface $mapping,
        LoggerInterface $logger, EventPlayerStatsFeedInterface $feed)
    {
        parent::__construct($repository, $mapping, $logger);
        $this->feed = $feed;
    }

    public function import($eventId)
    {
        $feedId = $this->mapping->getRemoteId(Event::class, $eventId);
        if ($feedId !== null) {
            $eventPlayers = $this->repository->findByEvent($eventId);
            if (count($eventPlayers) > 0) {
                $stats = $this->feed->getStats($feedId);
                foreach ($stats as $stat) {
                    if (! empty($stat['statistics'])) {
                        $ownPlayerId = $this->mapping->getOwnId(Player::class, $stat['player_id']);
                        foreach ($eventPlayers as $eventPlayer) {
                            if ($this->matchEventPlayer($eventPlayer, $stat['player_name'], $ownPlayerId) &&
                                 $eventPlayer->getStatistics() != $stat['statistics']) {
                                $eventPlayer->setStatistics($stat['statistics']);
                                $this->repository->update($eventPlayer);
                                $this->logger->info(
                                    NameUtil::shortClassName(get_class($this)) . ": Updated stats for EventPlayer:" .
                                         $eventPlayer->getEventId() . "-" . $eventPlayer->getPlayerName());
                            }
                        }
                    }
                }
            }
        }
    }

    private function matchEventPlayer(EventPlayer $eventPlayer, $statPlayerName, $statPlayerId)
    {
        if ($eventPlayer->getPlayer() !== null && $statPlayerId !== null) {
            return $eventPlayer->getPlayer()->getId() == $statPlayerId;
        } else {
            return $eventPlayer->getPlayerName() == $statPlayerName;
        }
    }
}