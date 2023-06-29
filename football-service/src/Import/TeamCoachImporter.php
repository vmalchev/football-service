<?php

namespace Sportal\FootballApi\Import;

use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistKeyFactory;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistRepository;
use Sportal\FootballApi\Repository\MappingRepositoryInterface;
use Sportal\FootballApi\Repository\TeamCoachRepository;
use Sportal\FootballApi\Repository\TeamRepository;
use Sportal\FootballFeedCommon\TeamFeedInterface;

class TeamCoachImporter
{

    private TeamCoachRepository $repository;

    private CoachImporter $coachImporter;

    private TeamRepository $teamRepository;

    private TeamFeedInterface $feed;

    private MappingRepositoryInterface $mapping;

    private IBlacklistRepository $blacklistRepository;

    private IBlacklistKeyFactory $blacklistKeyFactory;

    public function __construct(TeamCoachRepository $repository,
                                MappingRepositoryInterface $mapping,
                                TeamFeedInterface $feed,
                                CoachImporter $coachImporter,
                                TeamRepository $teamRepository,
                                IBlacklistRepository $blacklistRepository,
                                IBlacklistKeyFactory $blacklistKeyFactory)
    {
        $this->repository = $repository;
        $this->mapping = $mapping;
        $this->feed = $feed;
        $this->coachImporter = $coachImporter;
        $this->teamRepository = $teamRepository;
        $this->blacklistRepository = $blacklistRepository;
        $this->blacklistKeyFactory = $blacklistKeyFactory;
    }

    public function import(string $teamId)
    {
        $key = $this->blacklistKeyFactory->setEmpty()
            ->setEntity(BlacklistEntityName::TEAM())
            ->setEntityId($teamId)
            ->setType(BlacklistType::RELATION())
            ->setContext("coaches")
            ->create();
        if (!$this->blacklistRepository->exists($key)) {
            $team = $this->teamRepository->find($teamId);
            $teamFeedId = $this->mapping->getRemoteId($this->teamRepository->getModelClass(), $teamId);
            if ($team !== null && $teamFeedId !== null) {
                $teamCoaches = $this->feed->getCoaches($teamFeedId);
                $teamCoachModels = [];
                foreach ($teamCoaches as $personData) {
                    $personData['coach'] = $this->coachImporter->import($personData['coach_id']);
                    $personData['team'] = $team;
                    $teamCoachModels[] = $this->repository->createObject($personData);
                }
                $this->repository->upsert($team->getId(), $teamCoachModels);
            }
        }
    }

    public function importUpdatedAfter(\DateTimeInterface $afterTime)
    {
        $updatedAfter = $this->feed->getUpdatedCoaches($afterTime);
        foreach ($updatedAfter as $feedTeamId) {
            $domainTeamId = $this->mapping->getOwnId($this->teamRepository->getModelClass(), $feedTeamId);
            if ($domainTeamId !== null) {
                $this->import($domainTeamId);
            }
        }
    }
}