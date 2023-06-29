<?php

namespace Sportal\FootballApi\Import;

use Sportal\FootballApi\Adapter\EntityType;
use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerStatisticEntityFactory;
use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerStatisticItemFactory;
use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerStatisticRepository;
use Sportal\FootballApi\Domain\PlayerStatistic\OriginType;
use Sportal\FootballApi\Domain\PlayerStatistic\PlayerStatisticType;
use Sportal\FootballApi\Jobs\JobDispatcherInterface;
use Sportal\FootballApi\Repository\MappingRepositoryInterface;
use Sportal\FootballFeedCommon\MatchPlayerStatistic\MatchPlayerStatisticsFeedInterface;

class MatchPlayerStatisticImporter
{

    private MatchPlayerStatisticsFeedInterface $matchPlayerStatisticsFeed;

    private IPlayerStatisticRepository $playerStatisticRepository;

    private MappingRepositoryInterface $mappingRepositoryInterface;

    private IPlayerStatisticEntityFactory $playerStatisticEntityFactory;

    private IPlayerStatisticItemFactory $playerStatisticItemFactory;

    private JobDispatcherInterface $dispatcher;

    public function __construct(MatchPlayerStatisticsFeedInterface $matchPlayerStatisticsFeed,
                                IPlayerStatisticRepository $playerStatisticRepository,
                                MappingRepositoryInterface $mappingRepositoryInterface,
                                IPlayerStatisticEntityFactory $playerStatisticEntityFactory,
                                IPlayerStatisticItemFactory $playerStatisticItemFactory,
                                JobDispatcherInterface $dispatcher)
    {
        $this->matchPlayerStatisticsFeed = $matchPlayerStatisticsFeed;
        $this->playerStatisticRepository = $playerStatisticRepository;
        $this->mappingRepositoryInterface = $mappingRepositoryInterface;
        $this->playerStatisticEntityFactory = $playerStatisticEntityFactory;
        $this->playerStatisticItemFactory = $playerStatisticItemFactory;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @throws NoSuchEntityException
     */
    public function importMatchPlayerStatistics(string $matchId)
    {
        $remoteId = $this->mappingRepositoryInterface->getRemoteId(EntityType::MATCH()->getValue(), $matchId);

        if (is_null($remoteId)) {
            throw new NoSuchEntityException('No Enetpulse id mapping for match ' . $matchId);
        }

        $remoteStatistics = $this->matchPlayerStatisticsFeed->getMatchPlayerStatistics($remoteId);

        $entities = [];
        foreach ($remoteStatistics as $remoteStatistic) {
            $ownTeamId = $this->mappingRepositoryInterface
                ->getOwnId(EntityType::TEAM()->getValue(), $remoteStatistic['team_id']);
            $ownPlayerId = $this->mappingRepositoryInterface
                ->getOwnId(EntityType::PLAYER()->getValue(), $remoteStatistic['player_id']);
            $statistics = array_map(fn($statistic) => $this->playerStatisticItemFactory
                ->setName(new PlayerStatisticType($statistic['name']))
                ->setValue($statistic['value'])
                ->create(), $remoteStatistic['stats']);

            if (is_null($ownTeamId) || is_null($ownPlayerId)) {
                continue;
            }

            $entities[] = $this->playerStatisticEntityFactory
                ->setMatchId($matchId)
                ->setPlayerId($ownPlayerId)
                ->setTeamId($ownTeamId)
                ->setStatistics($statistics)
                ->setOrigin(OriginType::PRIMARY_PROVIDER())
                ->create();
        }

        $this->playerStatisticRepository->upsertByMatchId($matchId, $entities);
    }

    public function importRecentMatchPlayerStatistics(\DateTime $dateTime)
    {
        $matchIds = $this->matchPlayerStatisticsFeed->getRecentMatchPlayerStatistics($dateTime);

        foreach ($matchIds as $matchId) {
            $ownId = $this->mappingRepositoryInterface->getOwnId(EntityType::MATCH()->getValue(), $matchId['match_id']);
            if ($ownId !== null) {
                $this->dispatcher->dispatch('Import\MatchPlayerStatistics', [
                    $ownId
                ]);
            }
        }
    }

    public function dispatchJobsForMatchesWithDataProviderMapping()
    {
        $matchIds = $this->matchPlayerStatisticsFeed->getAllMatchPlayerStatistics();

        foreach ($matchIds as $matchId) {
            $ownId = $this->mappingRepositoryInterface->getOwnId(EntityType::MATCH()->getValue(), $matchId['match_id']);
            if ($ownId !== null) {
                $this->dispatcher->dispatch('Import\MatchPlayerStatistics', [
                    $ownId
                ]);
            }
        }
    }

}