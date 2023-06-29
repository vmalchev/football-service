<?php

namespace Sportal\FootballApi\Service\Statistic;

use Sportal\EnetpulseFootballParser\Shared\DB;
use Sportal\EnetpulseFootballParser\Statistic\PlayerStatistics;
use Sportal\FootballApi\Adapter\EntityAdapter;
use Sportal\FootballApi\Adapter\EntityType;
use Sportal\FootballApi\Adapter\MappingRequest;
use Sportal\FootballApi\Adapter\Provider;
use Sportal\FootballApi\Dto\IDto;
use Sportal\FootballApi\Dto\Season\SeasonDto;
use Sportal\FootballApi\Dto\Statistic\Player\OutputDto;
use Sportal\FootballApi\Repository\MlContentRepository;
use Sportal\FootballApi\Service\IService;

class PlayerStatistic implements IService
{
    /**
     * @var PlayerStatistics
     */
    private $playerStatisticsFeed;

    /**
     * @var EnetpulseMappingRepository
     */
    private $enetpulseMappingRepository;

    /**
     * @var EntityAdapter
     */
    private $entityAdapter;

    /**
     * @var MlContentRepository
     */
    private $mlContentRepository;

    public function __construct(
        DB $db,
        \Sportal\FootballApi\Infrastructure\Repository\EnetpulseMappingRepository $enetpulseMappingRepository,
        EntityAdapter $entityAdapter,
        MlContentRepository $mlContentRepository
    )
    {
        $this->playerStatisticsFeed = new PlayerStatistics($db);
        $this->entityAdapter = $entityAdapter;
        $this->enetpulseMappingRepository = $enetpulseMappingRepository;
        $this->mlContentRepository = $mlContentRepository;
    }

    public function process(IDto $inputDto)
    {
        if (
            !$inputDto->playerIds
            && !($inputDto->playerIds && $inputDto->seasonIds)
            && !($inputDto->teamId && $inputDto->seasonIds)
            && !($inputDto->playerIds && $inputDto->seasonIds && $inputDto->teamId)
        ) {
            throw new PlayerStatisticInputException('Invalid filter combination');
        }

        $teamId = null;
        if ($inputDto->teamId) {
            $teamId = $this->enetpulseMappingRepository->getMappingFromFeed(EntityType::TEAM()->getValue(), $inputDto->teamId)[$inputDto->teamId];
        }

        $playerIds = [];
        foreach ($inputDto->playerIds as $playerId) {
            if (isset($playerId)) {
                $playerIds[] = $this->enetpulseMappingRepository->getMappingFromFeed(EntityType::PLAYER()->getValue(), $playerId)[$playerId] ?? null;
            }
        }

        $seasonIds = [];
        foreach ($inputDto->seasonIds as $seasonId) {
            if (isset($seasonId)) {
                $seasonIds[] = $this->enetpulseMappingRepository->getMappingFromFeed(EntityType::SEASON()->getValue(), $seasonId)[$seasonId] ?? null;
            }
        }

        $statistics = $this->playerStatisticsFeed->getPlayerStatistics(
            $playerIds,
            $seasonIds,
            $teamId
        );

        $mappingRequests = [];
        foreach ($statistics as $statistic) {
            $mappingRequests[] =
                new MappingRequest(Provider::ENETPULSE(), new EntityType('player'), $statistic['player_id']);
            $mappingRequests[] =
                new MappingRequest(Provider::ENETPULSE(), new EntityType('team'), $statistic['team_id']);
            $mappingRequests[] =
                new MappingRequest(Provider::ENETPULSE(), new EntityType('tournament_season'), $statistic['season_id']);
        }
        if (empty($mappingRequests)) {
            return [];
        }

        $mappingContainer = $this->entityAdapter->getMappingContainerFromFeed($mappingRequests);

        $this->mlContentRepository->translate($mappingContainer->getAllEntities(), $inputDto->languageCode);

        $output = [];
        foreach ($statistics as $statistic) {
            $season = $mappingContainer->getByFeedId(Provider::ENETPULSE(), new EntityType('tournament_season'), $statistic['season_id']);
            if (is_null($season)) {
                continue;
            }

            $output[] = new OutputDto(
                $mappingContainer->getByFeedId(Provider::ENETPULSE(), new EntityType('player'), $statistic['player_id']),
                $mappingContainer->getByFeedId(Provider::ENETPULSE(), new EntityType('team'), $statistic['team_id']),
                new SeasonDto($season->getId(), $season->getName(), $season->getActive()),
                $season->getTournament(),
                [
                    'goals' => (int)$statistic['goals'],
                    'played' => (int)$statistic['played'],
                    'assists' => (int)$statistic['assists'],
                    'minutes' => (int)$statistic['minutes'],
                    'started' => (int)$statistic['started'],
                    'yellow_cards' => (int)$statistic['yellow_cards'],
                    'red_cards' => (int)$statistic['red_cards'],
                    'goals_substitute' => (int)$statistic['goals_substitute'],
                    'substitute_in' => (int)$statistic['substitute_in'],
                    'minutes_substitute' => (int)$statistic['minutes_substitute'],
                    'substitute_out' => (int)$statistic['substitute_out'],
                    'conceded' => (int)$statistic['conceded'],
                    'cleansheets' => (int)$statistic['cleansheets'],
                ],
                $statistic['position']
            );
        }

        return $output;
    }
}
