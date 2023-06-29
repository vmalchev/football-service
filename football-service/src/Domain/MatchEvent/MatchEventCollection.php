<?php


namespace Sportal\FootballApi\Domain\MatchEvent;


use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistKey;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistKeyFactory;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistRepository;
use Sportal\FootballApi\Domain\Database\ITransactionManager;
use Sportal\FootballApi\Domain\Match\IMatchEntity;

class MatchEventCollection implements IMatchEventCollection
{
    private IBlacklistRepository $blacklistRepository;

    private IMatchEventRepository $matchEventRepository;

    private IBlacklistKeyFactory $blacklistFactory;

    private IMatchEntity $matchEntity;

    /**
     * @var IMatchEventEntity[]
     */
    private array $events;

    private ?IBlacklistKey $blacklistKey = null;

    private ITransactionManager $transactionManager;

    /**
     * MatchEventCollection constructor.
     * @param IBlacklistRepository $blacklistRepository
     * @param IMatchEventRepository $matchEventRepository
     * @param IBlacklistKeyFactory $blacklistFactory
     * @param ITransactionManager $transactionManager
     */
    public function __construct(IBlacklistRepository $blacklistRepository,
                                IMatchEventRepository $matchEventRepository,
                                IBlacklistKeyFactory $blacklistFactory,
                                ITransactionManager $transactionManager)
    {
        $this->blacklistRepository = $blacklistRepository;
        $this->matchEventRepository = $matchEventRepository;
        $this->blacklistFactory = $blacklistFactory;
        $this->transactionManager = $transactionManager;
    }

    /**
     * @return IMatchEntity
     */
    public function getMatch(): IMatchEntity
    {
        return $this->matchEntity;
    }

    /**
     * @param IMatchEntity $matchEntity
     * @return IMatchEventCollection
     */
    public function setMatch(IMatchEntity $matchEntity): IMatchEventCollection
    {
        $collection = clone $this;
        $collection->matchEntity = $matchEntity;
        return $collection;
    }

    /**
     * @return array
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    /**
     * @param array $events
     * @return IMatchEventCollection
     */
    public function setEvents(array $events): IMatchEventCollection
    {
        $collection = clone $this;
        $collection->events = $events;
        return $collection;
    }

    public function withBlacklist(): IMatchEventCollection
    {
        $collection = clone $this;
        $collection->blacklistKey = $this->blacklistFactory->setEmpty()
            ->setType(BlacklistType::RELATION())
            ->setEntity(BlacklistEntityName::MATCH())
            ->setEntityId($this->matchEntity->getId())
            ->setContext('events')
            ->create();
        return $collection;
    }

    public function upsert(): IMatchEventCollection
    {
        return $this->transactionManager->transactional(function () {
            $this->matchEventRepository->deleteByMatch($this->matchEntity);
            $updatedEvents = [];
            foreach ($this->events as $event) {
                if ($event->getId() !== null) {
                    $this->matchEventRepository->update($event);
                    $updatedEvents[] = $event;
                } else {
                    $updatedEvents[] = $this->matchEventRepository->insert($event);
                }
            }
            if ($this->blacklistKey !== null) {
                $this->blacklistRepository->upsert($this->blacklistKey);
            }
            return $this->setEvents($updatedEvents);
        });
    }
}