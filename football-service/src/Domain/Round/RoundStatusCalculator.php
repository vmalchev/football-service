<?php

namespace Sportal\FootballApi\Domain\Round;


class RoundStatusCalculator
{

    const HOURS_48 = 60 * 60 * 48;

    private IRoundEntityFactory $entityFactory;

    /**
     * @param IRoundEntityFactory $entityFactory
     */
    public function __construct(IRoundEntityFactory $entityFactory)
    {
        $this->entityFactory = $entityFactory;
    }

    /**
     * @param IRoundEntity[] $rounds
     * @return IRoundEntity[]
     */
    public function applyStatuses(array $rounds): array
    {
        if (empty($rounds)) {
            return $rounds;
        }

        $activeRoundId = $this->getActiveRoundId($rounds);

        $results = [];

        foreach ($rounds as $round) {
            $results[] = $this->entityFactory
                ->setId($round->getId())
                ->setKey($round->getKey())
                ->setName($round->getName())
                ->setType($round->getType())
                ->setStartDate($round->getStartDate())
                ->setEndDate($round->getEndDate())
                ->setRoundStatus($activeRoundId == $round->getId() ? RoundStatus::ACTIVE() : RoundStatus::INACTIVE())
                ->create();
        }

        return $results;
    }

    /**
     * Find the round with the nearest start time to current time. Nearest means the smallest absolute difference
     * in seconds between now and start_time.
     * a) If the current time is after end_time+48h of the found nearest round and there is a next round, mark the
     * next round as ACTIVE (regardless of how far it is ahead)
     * b) In all other cases the nearest round is active
     *
     * @param IRoundEntity[] $rounds
     * @return int
     */
    private function getActiveRoundId(array $rounds): int
    {
        $currentDate = new \DateTime();

        $minOffset = null;
        $activeRoundId = null;
        $activeRoundIndex = null;
        foreach ($rounds as $index => $round) {
            $offset = abs($currentDate->getTimestamp() - $round->getStartDate()->getTimestamp());
            if (is_null($minOffset) || $offset < $minOffset) {
                $minOffset = $offset;
                $activeRoundId = $round->getId();
                $activeRoundIndex = $index;
            }
        }

        if ($activeRoundIndex < count($rounds) - 1) {
            $postRoundOffset = $currentDate->getTimestamp() - $rounds[$activeRoundIndex]->getEndDate()->getTimestamp();
            if ($postRoundOffset > self::HOURS_48) {
                $activeRoundId = $rounds[$activeRoundIndex + 1]->getId();
            }
        }

        return $activeRoundId;
    }
}