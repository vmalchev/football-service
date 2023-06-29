<?php


namespace Sportal\FootballApi\Domain\Lineup;


use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistKey;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistKeyFactory;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistRepository;
use Sportal\FootballApi\Domain\Database\ITransactionManager;

class LineupModel implements ILineupModel
{
    private ILineupProfile $lineupProfile;
    private ?IBlacklistKey $blacklistKey = null;

    private ILineupRepository $repository;
    private ITransactionManager $transactionManager;
    private IBlacklistRepository $blacklistRepository;
    private IBlacklistKeyFactory $blacklistKeyFactory;
    private ILineupPlayerRepository $playerRepository;

    /**
     * LineupModel constructor.
     * @param ILineupRepository $repository
     * @param ITransactionManager $transactionManager
     * @param IBlacklistRepository $blacklistRepository
     * @param IBlacklistKeyFactory $blacklistKeyFactory
     * @param ILineupPlayerRepository $playerRepository
     */
    public function __construct(ILineupRepository $repository,
                                ITransactionManager $transactionManager,
                                IBlacklistRepository $blacklistRepository,
                                IBlacklistKeyFactory $blacklistKeyFactory,
                                ILineupPlayerRepository $playerRepository)
    {
        $this->repository = $repository;
        $this->transactionManager = $transactionManager;
        $this->blacklistRepository = $blacklistRepository;
        $this->blacklistKeyFactory = $blacklistKeyFactory;
        $this->playerRepository = $playerRepository;
    }

    /**
     * @return ILineupProfile
     */
    public function getLineupProfile(): ILineupProfile
    {
        return $this->lineupProfile;
    }

    /**
     * @param ILineupProfile $lineupProfile
     * @return LineupModel
     */
    public function setLineupProfile(ILineupProfile $lineupProfile): LineupModel
    {
        $this->lineupProfile = $lineupProfile;
        return $this;
    }

    public function withBlacklist(): ILineupModel
    {
        $model = clone $this;
        $model->blacklistKey = $this->blacklistKeyFactory->setEmpty()
            ->setType(BlacklistType::RELATION())
            ->setEntity(BlacklistEntityName::MATCH())
            ->setEntityId($this->lineupProfile->getLineup()->getMatchId())
            ->setContext('lineup')
            ->create();
        return $model;
    }

    public function upsert(): void
    {
        $this->transactionManager->transactional(function () {
            $this->repository->upsert($this->lineupProfile->getLineup());
            $this->playerRepository->upsertByLineup($this->lineupProfile->getLineup(), $this->lineupProfile->getPlayers());
            if (!is_null($this->blacklistKey)) {
                $this->blacklistRepository->upsert($this->blacklistKey);
            }
        });
    }
}