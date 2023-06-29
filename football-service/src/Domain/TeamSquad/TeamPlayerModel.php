<?php


namespace Sportal\FootballApi\Domain\TeamSquad;


use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistKeyFactory;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistRepository;
use Sportal\FootballApi\Domain\Database\ITransactionManager;
use Sportal\FootballApi\Domain\Team\ITeamEntity;

class TeamPlayerModel implements ITeamPlayerModel
{
    private array $players;

    private bool $upsertByTeam;

    private ?array $blacklistKeys = null;

    private ITeamPlayerRepository $playerTeamsRepository;

    private ITransactionManager $transactionManager;

    private IBlacklistRepository $blacklistRepository;

    private IBlacklistKeyFactory $blacklistKeyFactory;

    private ?ITeamEntity $teamEntity = null;

    /**
     * PlayerTeamsModel constructor.
     * @param ITeamPlayerRepository $teamPlayerRepository
     * @param ITransactionManager $transactionManager
     * @param IBlacklistRepository $blacklistRepository
     * @param IBlacklistKeyFactory $blacklistKeyFactory
     */
    public function __construct(ITeamPlayerRepository $teamPlayerRepository,
                                ITransactionManager $transactionManager,
                                IBlacklistRepository $blacklistRepository,
                                IBlacklistKeyFactory $blacklistKeyFactory)
    {
        $this->playerTeamsRepository = $teamPlayerRepository;
        $this->transactionManager = $transactionManager;
        $this->blacklistRepository = $blacklistRepository;
        $this->blacklistKeyFactory = $blacklistKeyFactory;
    }

    public function upsert(): ITeamPlayerModel
    {
        $this->transactionManager->transactional(function () {
            if ($this->upsertByTeam) {
                foreach ($this->getTeamIds() as $teamId) {
                    $this->playerTeamsRepository->deleteByTeam($teamId);
                }
            }

            $this->playerTeamsRepository->upsert($this->players);

            if ($this->blacklistKeys !== null) {
                foreach ($this->blacklistKeys as $blacklistKey) {
                    $this->blacklistRepository->upsert($blacklistKey);
                }
            }
        });

        return $this;
    }

    public function withBlacklist(): ITeamPlayerModel
    {
        $update = clone $this;

        foreach ($this->getTeamIds() as $teamId) {
            $update->blacklistKeys[] = $this->blacklistKeyFactory->setEmpty()
                ->setType(BlacklistType::RELATION())
                ->setEntity(BlacklistEntityName::TEAM())
                ->setEntityId($teamId)
                ->setContext('players')
                ->create();
        }
        return $update;
    }

    /**
     * @param ITeamPlayerEntity[] $players
     * @return ITeamPlayerModel
     */
    public function setTeamPlayers(array $players): ITeamPlayerModel
    {
        $update = clone $this;
        $update->players = $players;
        return $update;
    }

    public function setTeamEntity(ITeamEntity $teamEntity): ITeamPlayerModel
    {
        $model = clone $this;
        $model->teamEntity = $teamEntity;
        return $model;
    }

    private function getTeamIds(): array
    {
        $teamIds = [];
        if (empty($this->players) && $this->teamEntity !== null) {
            $teamIds[] = $this->teamEntity->getId();
        } else {
            $teamIds = array_unique(array_map(fn($player) => $player->getTeamId(), $this->players));
        }

        return $teamIds;
    }

    /**
     * @return ITeamPlayerEntity[]
     */
    public function getPlayers(): array
    {
        return $this->players;
    }

    /**
     * @return ITeamPlayerEntity[]
     */
    public function getActivePlayers(): array
    {
        return array_filter($this->players, fn($player) => $player->getStatus()->equals(TeamSquadStatus::ACTIVE()));
    }

    /**
     * @param bool $upsertByTeam
     * @return ITeamPlayerModel
     */
    public function setUpsertByTeam(bool $upsertByTeam): ITeamPlayerModel
    {
        $update = clone $this;
        $update->upsertByTeam = $upsertByTeam;

        return $update;
    }

}