<?php

namespace Sportal\FootballApi\Domain\Stage;

class StageStatusCalculator
{

    const TWO_DAYS = 60 * 60 * 24 * 2;
    const SEVEN_DAYS = 60 * 60 * 24 * 7;


    /**
     * 1. Order stages by start_date.
     * 2. Look for stages for which today is >= start_date and today is <= end+date + 2 days. Mark them as ACTIVE.
     * 2.1. If no active stages are found mark the single stage with the nearest start_date to today as ACTIVE.
     * 3. Finally mark all stages which start before/after 7 days of the start_date of the first ACTIVE stage found in the list as ACTIVE as well.
     * @param IStageEntity[] $stages
     * @return IStageEntity[]
     */
    public function applyStatuses(array $stages): array
    {
        if (empty($stages)) {
            return $stages;
        }

        $currentDate = new \DateTime();
        $firstActiveStageFoundIndex = null;
        $minOffset = null;
        $minOffsetIndex = null;

        $stagesToParse = [];

        foreach ($stages as $stage) {
            if (is_null($stage->getStartDate()) || is_null($stage->getEndDate())) {
                $stage->setStageStatus(StageStatus::INACTIVE());
            } else {
                $stagesToParse[] = $stage;
            }
        }

        // Handle the absence of any valid stages, i.e. without a start date and/or an end date
        // 1. If there's only a single stage in the season - mark it as active
        // 2. If there are multiple stages, leave them all as inactive
        if (empty($stagesToParse)) {
            if (sizeof($stages) == 1) {
                $stages[0]->setStageStatus(StageStatus::ACTIVE());
            }

            return $stages;
        }

        usort($stagesToParse, function($a, $b) {return $a->getStartDate() > $b->getStartDate();});

        foreach ($stagesToParse as $index => $stage) {
            $stageStatus = $this->getStageStatus($currentDate, $stage);
            if (is_null($firstActiveStageFoundIndex) && $stageStatus->equals(StageStatus::ACTIVE())) {
                $firstActiveStageFoundIndex = $index;
            }

            $stage->setStageStatus($stageStatus);

            $offset = abs($currentDate->getTimestamp() - $stage->getStartDate()->getTimestamp());
            if (is_null($minOffset) || $minOffset > $offset) {
                $minOffset = $offset;
                $minOffsetIndex = $index;
            }
        }

        if (is_null($firstActiveStageFoundIndex)) {
            $firstActiveStageFoundIndex = $minOffsetIndex;

            $stagesToParse[$firstActiveStageFoundIndex]->setStageStatus(StageStatus::ACTIVE());
        }

        foreach ($stagesToParse as $stageToParse) {
            if (abs($stageToParse->getStartDate()->getTimestamp() -
                    $stagesToParse[$firstActiveStageFoundIndex]->getStartDate()->getTimestamp()) <= self::SEVEN_DAYS) {
                $stageToParse->setStageStatus(StageStatus::ACTIVE());
            }
        }

        return $stages;
    }

    /**
     * @param \DateTimeInterface $currentDate
     * @param IStageEntity $stage
     * @return StageStatus
     */
    private function getStageStatus(\DateTimeInterface $currentDate, IStageEntity $stage): StageStatus
    {
        if ($currentDate->getTimestamp() >= $stage->getStartDate()->getTimestamp() &&
            $currentDate->getTimestamp() <= ($stage->getEndDate()->getTimestamp() + self::TWO_DAYS)) {
            return StageStatus::ACTIVE();
        }

        return StageStatus::INACTIVE();
    }
}