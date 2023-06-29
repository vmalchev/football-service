<?php


namespace Sportal\FootballApi\Domain\TeamSquad;


use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistKey;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistKeyFactory;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistRepository;
use Sportal\FootballApi\Domain\Database\ITransactionManager;
use Sportal\FootballApi\Domain\Team\ITeamEntity;

class TeamCoachModel implements ITeamCoachModel
{
    private ITeamCoachRepository $teamCoachRepository;

    private IBlacklistKeyFactory $blacklistKeyFactory;

    private IBlacklistRepository $blacklistRepository;

    private ITransactionManager $transactionManager;

    private ITeamEntity $team;

    /**
     * @var ITeamCoachEntity[]
     */
    private array $coaches;

    private ?IBlacklistKey $blacklistKey = null;

    /**
     * TeamCoachModel constructor.
     * @param ITeamCoachRepository $teamCoachRepository
     * @param IBlacklistKeyFactory $blacklistKeyFactory
     * @param IBlacklistRepository $blacklistRepository
     * @param ITransactionManager $transactionManager
     */
    public function __construct(ITeamCoachRepository $teamCoachRepository, IBlacklistKeyFactory $blacklistKeyFactory, IBlacklistRepository $blacklistRepository, ITransactionManager $transactionManager)
    {
        $this->teamCoachRepository = $teamCoachRepository;
        $this->blacklistKeyFactory = $blacklistKeyFactory;
        $this->blacklistRepository = $blacklistRepository;
        $this->transactionManager = $transactionManager;
    }


    public function setTeamCoaches(ITeamEntity $team, array $coaches): ITeamCoachModel
    {
        $model = clone $this;
        $model->team = $team;
        $model->coaches = $coaches;
        return $model;
    }

    public function upsert(): ITeamCoachModel
    {
        $this->transactionManager->transactional(function () {
            $this->teamCoachRepository->upsertByTeam($this->team, $this->coaches);
            if (!is_null($this->blacklistKey)) {
                $this->blacklistRepository->upsert($this->blacklistKey);
            }
        });
        return $this;
    }

    public function withBlacklist(): TeamCoachModel
    {
        $model = clone $this;
        $model->blacklistKey = $this->blacklistKeyFactory->setEmpty()
            ->setType(BlacklistType::RELATION())
            ->setEntity(BlacklistEntityName::TEAM())
            ->setEntityId($this->team->getId())
            ->setContext('coaches')
            ->create();
        return $model;
    }

}