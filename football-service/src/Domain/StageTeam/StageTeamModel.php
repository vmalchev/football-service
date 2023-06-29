<?php


namespace Sportal\FootballApi\Domain\StageTeam;


use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistKeyFactory;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistRepository;
use Sportal\FootballApi\Domain\Database\ITransactionManager;
use Sportal\FootballApi\Domain\Stage\IStageEntity;

class StageTeamModel implements IStageTeamModel
{

    private IStageTeamRepository $stageTeamRepository;

    private IBlacklistKeyFactory $blacklistKeyFactory;

    private IBlacklistRepository $blacklistRepository;

    private ITransactionManager $transactionManager;

    private array $stageTeams;

    private IStageEntity $stageEntity;

    private bool $hasBlacklist = false;

    public function __construct(IStageTeamRepository $stageTeamRepository,
                                IBlacklistKeyFactory $blacklistKeyFactory,
                                IBlacklistRepository $blacklistRepository,
                                ITransactionManager $transactionManager)
    {
        $this->stageTeamRepository = $stageTeamRepository;
        $this->blacklistKeyFactory = $blacklistKeyFactory;
        $this->blacklistRepository = $blacklistRepository;
        $this->transactionManager = $transactionManager;
    }

    public function setStageTeams(array $stageTeams, IStageEntity $stageEntity): StageTeamModel
    {
        $model = clone $this;
        $model->stageTeams = $stageTeams;
        $model->stageEntity = $stageEntity;
        return $model;
    }

    public function getStageTeams(): array
    {
        return $this->stageTeams;
    }

    public function withBlacklist(): StageTeamModel
    {
        $model = clone $this;
        $model->hasBlacklist = true;
        return $model;
    }

    public function update(): void
    {
        $this->transactionManager->transactional(function () {
            $this->stageTeamRepository->upsertByStage($this->stageEntity, $this->stageTeams);

            if ($this->hasBlacklist) {
                $this->upsertBlacklist($this->stageEntity->getId(), BlacklistEntityName::STAGE());
            }
        });
    }

    private function upsertBlacklist($id, BlacklistEntityName $blacklistEntityName)
    {
        $blacklistKey = $this->blacklistKeyFactory
            ->setType(BlacklistType::RELATION())
            ->setEntity($blacklistEntityName)
            ->setEntityId($id)
            ->setContext('teams')
            ->create();

        $this->blacklistRepository->upsert($blacklistKey);
    }

}