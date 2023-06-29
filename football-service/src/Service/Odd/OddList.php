<?php

namespace Sportal\FootballApi\Service\Odd;


use Sportal\FootballApi\Dto\IDto;
use Sportal\FootballApi\Dto\Odd\InputDto;
use Sportal\FootballApi\Dto\Odd\OddsType;
use Sportal\FootballApi\Model\Odd;
use Sportal\FootballApi\Repository\LiveOddRepository;
use Sportal\FootballApi\Repository\OddLinkRepository;
use Sportal\FootballApi\Repository\OddRepository;
use Sportal\FootballApi\Service\IService;
use Sportal\FootballApi\Util\ArrayUtil;

class OddList implements IService
{
    /**
     * @var OddRepository
     */
    private $oddRepository;

    /**
     * @var LiveOddRepository
     */
    private $liveOddRepository;

    /**
     * @var OddLinkRepository
     */
    private $oddLinkRepository;

    public function __construct(
        OddRepository $oddRepository,
        LiveOddRepository $liveOddRepository,
        OddLinkRepository $oddLinkRepository
    )
    {
        $this->oddRepository = $oddRepository;
        $this->liveOddRepository = $liveOddRepository;
        $this->oddLinkRepository = $oddLinkRepository;
    }

    public function process(IDto $inputDto)
    {
        /**
         * @var InputDto $inputDto
         */
        $oddLinkMap = ArrayUtil::toMap($this->oddLinkRepository->findByClient($inputDto->getOddClientCode()),
            function ($oddLink) {
                return $oddLink->getOddProvider()->getId();
            });

        $oddProviderIds = array_keys($oddLinkMap);

        if (empty($oddProviderIds)) {
            return [];
        }

        if ($inputDto->getOddsType() == OddsType::PREMATCH) {
            $allOdds = $this->oddRepository->findByKeys($oddProviderIds, $inputDto->getEventIds(), $inputDto->getOddFormat());
        } else if ($inputDto->getOddsType() == OddsType::LIVE) {
            $allOdds = $this->liveOddRepository->findByKeys($oddProviderIds, $inputDto->getEventIds(), $inputDto->getOddFormat());
        } else {
            $preMatch = $this->oddRepository->findByKeys($oddProviderIds, $inputDto->getEventIds(), $inputDto->getOddFormat());
            $live = $this->liveOddRepository->findByKeys($oddProviderIds, $inputDto->getEventIds(), $inputDto->getOddFormat());
            $allOdds = array_merge($preMatch, $live);
        }
        $groupedOdds = [];
        foreach ($allOdds as $oddModel) {
            $oddLink = $oddLinkMap[$oddModel->getOddProvider()->getId()];
            $oddModel->setSortOrder($oddLink->getSortorder())
                ->setLinks($oddLink->getLinks());
            // avoid duplicates by $eventId-$oddProviderId
            $groupedOdds[$oddModel->getEventId()][$oddModel->getOddProvider()->getId()] = $oddModel;
        }
        $results = [];
        foreach ($groupedOdds as $eventId => $oddModels) {
            usort($oddModels, fn(Odd $a, Odd $b) => $a->getSortOrder() - $b->getSortOrder());
            $results[] = [
                'event_id' => (string)$eventId,
                'odds' => array_values($oddModels)
            ];
        }
        return $results;
    }
}
