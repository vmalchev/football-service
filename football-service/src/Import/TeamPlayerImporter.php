<?php

namespace Sportal\FootballApi\Import;

use Psr\Log\LoggerInterface;
use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistKeyFactory;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistRepository;
use Sportal\FootballApi\Model\PartialTeam;
use Sportal\FootballApi\Repository\MappingRepositoryInterface;
use Sportal\FootballApi\Repository\TeamPlayerRepository;
use Sportal\FootballApi\Repository\TeamRepository;
use Sportal\FootballFeedCommon\TeamFeedInterface;

class TeamPlayerImporter
{
    private TeamPlayerRepository $repository;

    private PlayerImporter $playerImporter;

    private TeamRepository $teamRepository;

    private TeamFeedInterface $feed;

    private MappingRepositoryInterface $mapping;

    private LoggerInterface $logger;

    private IBlacklistRepository $blacklistRepository;

    private IBlacklistKeyFactory $blacklistKeyFactory;

    public function __construct(TeamPlayerRepository $repository,
                                MappingRepositoryInterface $mapping,
                                TeamFeedInterface $feed,
                                PlayerImporter $playerImporter,
                                TeamRepository $teamRepository,
                                LoggerInterface $logger,
                                IBlacklistRepository $blacklistRepository,
                                IBlacklistKeyFactory $blacklistKeyFactory)
    {
        $this->repository = $repository;
        $this->mapping = $mapping;
        $this->feed = $feed;
        $this->playerImporter = $playerImporter;
        $this->teamRepository = $teamRepository;
        $this->logger = $logger;
        $this->blacklistRepository = $blacklistRepository;
        $this->blacklistKeyFactory = $blacklistKeyFactory;
    }

    public function import($teamId)
    {
        $team = $this->teamRepository->find($teamId);
        if ($team !== null) {
            $this->importTeam($team);
        }
    }

    public function importAll($source = null)
    {
        $teams = $this->teamRepository->findAll();
        foreach ($teams as $team) {
            $this->logger->info('Importing Players for ' . $team->getId() . ' ' . $team->getName());
            $this->importTeam($team);
        }
    }

    protected function importTeam(PartialTeam $team)
    {
        $key = $this->blacklistKeyFactory->setEmpty()
            ->setEntity(BlacklistEntityName::TEAM())
            ->setEntityId($team->getId())
            ->setType(BlacklistType::RELATION())
            ->setContext("players")->create();
        if (!$this->blacklistRepository->exists($key)) {
            $teamFeedId = $this->mapping->getRemoteId($this->teamRepository->getModelClass(), $team->getId());
            if ($teamFeedId !== null) {
                $teamPlayers = $this->feed->getPlayers($teamFeedId);
                $teamPlayerModels = [];
                foreach ($teamPlayers as $teamPlayerArr) {
                    $teamPlayerArr['player'] = $this->playerImporter->import($teamPlayerArr['player_id']);
                    $teamPlayerArr['team'] = $team;
                    $teamPlayerModels[] = $this->repository->createObject($teamPlayerArr);
                }
                $this->repository->upsert($team->getId(), $teamPlayerModels);
            }

        }
    }

    public function importUpdatedAfter(\DateTimeInterface $afterTime)
    {
        $updatedAfter = $this->feed->getUpdatedPlayers($afterTime);
        foreach ($updatedAfter as $feedTeamId) {
            $domainTeamId = $this->mapping->getOwnId($this->teamRepository->getModelClass(), $feedTeamId);
            if ($domainTeamId !== null) {
                $this->import($domainTeamId);
            }
        }
    }
}