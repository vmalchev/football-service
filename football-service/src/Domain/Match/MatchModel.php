<?php


namespace Sportal\FootballApi\Domain\Match;


use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistKeyFactory;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistRepository;
use Sportal\FootballApi\Domain\Database\ITransactionManager;

class MatchModel implements IMatchModel
{
    private IMatchRepository $matchRepository;

    private IBlacklistKeyFactory $blacklistFactory;

    private IBlacklistRepository $blacklistRepository;

    private ITransactionManager $transactionManager;

    private IMatchEntity $matchEntity;

    private bool $hasBlacklist = false;

    /**
     * MatchModel constructor.
     * @param IMatchRepository $matchRepository
     * @param IBlacklistKeyFactory $blacklistFactory
     * @param IBlacklistRepository $blacklistRepository
     * @param ITransactionManager $transactionManager
     */
    public function __construct(IMatchRepository $matchRepository, IBlacklistKeyFactory $blacklistFactory, IBlacklistRepository $blacklistRepository, ITransactionManager $transactionManager)
    {
        $this->matchRepository = $matchRepository;
        $this->blacklistFactory = $blacklistFactory;
        $this->blacklistRepository = $blacklistRepository;
        $this->transactionManager = $transactionManager;
    }

    public function setMatch(IMatchEntity $matchEntity): IMatchModel
    {
        $model = clone $this;
        $model->matchEntity = $matchEntity;
        return $model;
    }

    public function getMatch(): IMatchEntity
    {
        return $this->matchEntity;
    }

    public function withBlacklist(): IMatchModel
    {
        $model = clone $this;
        $model->hasBlacklist = true;
        return $model;
    }

    public function create(): IMatchModel
    {
        return $this->transactionManager->transactional(function () {
            $insertedMatch = $this->matchRepository->insert($this->matchEntity);
            if ($this->hasBlacklist) {
                $this->upsertBlacklist($insertedMatch);
            }
            return $this->setMatch($insertedMatch);
        });
    }

    public function update(): IMatchModel
    {
        $this->transactionManager->transactional(function () {
            $this->matchRepository->update($this->matchEntity);
            if ($this->hasBlacklist) {
                $this->upsertBlacklist($this->matchEntity);
            }
        });
        return $this;
    }

    private function upsertBlacklist(IMatchEntity $matchEntity): void
    {
        $blacklistKey = $this->blacklistFactory->setType(BlacklistType::ENTITY())
            ->setEntity(BlacklistEntityName::MATCH())
            ->setEntityId($matchEntity->getId())
            ->create();
        $this->blacklistRepository->upsert($blacklistKey);

    }
}