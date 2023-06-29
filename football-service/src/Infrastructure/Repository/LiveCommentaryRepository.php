<?php

namespace Sportal\FootballApi\Infrastructure\Repository;

use Sportal\FootballApi\Adapter\EntityAdapter;
use Sportal\FootballApi\Adapter\EntityType;
use Sportal\FootballApi\Adapter\MappingRequest;
use Sportal\FootballApi\Adapter\Provider;
use Sportal\FootballApi\Domain\LiveCommentary\ILiveCommentaryEntity;
use Sportal\FootballApi\Domain\LiveCommentary\ILiveCommentaryRepository;
use Sportal\FootballApi\Infrastructure\Builder\LiveCommentaryEntityBuilder;
use Sportal\FootballApi\Repository\EventRepository;
use Sportal\FootballApi\Repository\MlContentRepository;
use Sportal\FootballFeedCommon\Commentary\CommentaryFeedInterface;

class LiveCommentaryRepository implements ILiveCommentaryRepository
{

    /**
     * @var CommentaryFeedInterface
     */
    private $commentaryFeed;

    /**
     * @var LiveCommentaryEntityBuilder
     */
    private $liveCommentaryEntityBuilder;

    /**
     * @var EnetpulseMappingRepository
     */
    private $enetpulseMappingRepository;

    /**
     * @var EventRepository
     */
    private $matchRepository;

    /**
     * @var EntityAdapter
     */
    private $entityAdapter;

    private $mlContentRepository;

    public function __construct(CommentaryFeedInterface $commentaryFeed, LiveCommentaryEntityBuilder $liveCommentaryEntityBuilder,
                                EnetpulseMappingRepository $enetpulseMappingRepository, EventRepository $matchRepository, EntityAdapter $entityAdapter,
                                MlContentRepository $mlContentRepository)
    {
        $this->commentaryFeed = $commentaryFeed;
        $this->liveCommentaryEntityBuilder = $liveCommentaryEntityBuilder;
        $this->enetpulseMappingRepository = $enetpulseMappingRepository;
        $this->matchRepository = $matchRepository;
        $this->entityAdapter = $entityAdapter;
        $this->mlContentRepository = $mlContentRepository;
    }

    /**
     * @param int $matchId
     * @param string $languageCode
     * @return ILiveCommentaryEntity[]
     */
    public function findByMatchIdAndLanguageCode(int $matchId, string $languageCode): array
    {
        $enetpulseMatchMapping = $this->enetpulseMappingRepository->getMappingFromFeed(EntityType::MATCH()->getValue(), $matchId);
        if (!isset($enetpulseMatchMapping[$matchId])) {
            return [];
        }
        $enetpulseMatchId = $enetpulseMatchMapping[$matchId];

        $liveCommentaries = $this->commentaryFeed->getCommentary($enetpulseMatchId, $languageCode);

        $detailMappingRequests = [];

        foreach ($liveCommentaries as $liveCommentaryFeed) {
            foreach ($liveCommentaryFeed['details'] as $detail) {
                if (EntityType::isValid($detail['type'])) {
                    $detailMappingRequests[] = new MappingRequest(Provider::ENETPULSE(), new EntityType($detail['type']), $detail['id']);
                }
            }
        }
        if (empty($detailMappingRequests)) {
            return [];
        }


        $mappingContainer = $this->entityAdapter->getMappingContainerFromFeed($detailMappingRequests);
        $this->mlContentRepository->translate($mappingContainer->getAllEntities(), $languageCode);

        foreach ($liveCommentaries as &$liveCommentaryFeed) {
            foreach ($liveCommentaryFeed['details'] as &$detail) {
                if ($detail['type'] === 'venue') {
                    $venue = $this->matchRepository->find($matchId)->getVenue();
                    if ($venue !== null) {
                        $this->mlContentRepository->translate([
                            $venue
                        ], $languageCode);
                        $detail['data'] = $venue;
                    }
                } elseif (EntityType::isValid($detail['type'])) {
                    $detail['data'] = $mappingContainer->getByFeedId(Provider::ENETPULSE(), new EntityType($detail['type']), $detail['id']);
                }
            }
        }

        return array_map(function (array $liveCommentary) {
            return $this->liveCommentaryEntityBuilder->create($liveCommentary);
        }, $liveCommentaries);
    }
}