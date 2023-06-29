<?php


namespace Sportal\FootballApi\Domain\Match\Specification;


use Sportal\FootballApi\Domain\Match\Exception\InvalidMatchException;
use Sportal\FootballApi\Domain\Match\IMatchEntity;
use Sportal\FootballApi\Domain\MatchStatus\MatchStatusType;

class MatchSpecification
{

    /**
     * @param IMatchEntity $matchEntity
     * @throws InvalidMatchException
     */
    public function validate(IMatchEntity $matchEntity): void
    {
        if ($matchEntity->getStage() === null) {
            throw new InvalidMatchException("Invalid stageId:{$matchEntity->getStageId()}");
        }
        if ($matchEntity->getStatus() === null) {
            throw new InvalidMatchException("Invalid match statusId:{$matchEntity->getStatusId()}");
        }

        if ($matchEntity->getGroupId() !== null && $matchEntity->getGroup() === null) {
            throw new InvalidMatchException("Invalid match groupId:{$matchEntity->getGroupId()}");
        } else if ($matchEntity->getGroup() !== null && $matchEntity->getGroup()->getStageId() != $matchEntity->getStage()->getId()) {
            throw new InvalidMatchException("groupId:{$matchEntity->getGroupId()} must be part of stageId:{$matchEntity->getStage()->getId()}");
        }

        if ($matchEntity->getHomeTeamId() !== null && $matchEntity->getHomeTeam() === null) {
            throw new InvalidMatchException("Invalid match homeTeamId:{$matchEntity->getGroupId()}");
        }
        if ($matchEntity->getAwayTeamId() !== null && $matchEntity->getAwayTeam() === null) {
            throw new InvalidMatchException("Invalid match awayTeamId:{$matchEntity->getGroupId()}");
        }
        if ($matchEntity->getVenueId() !== null && $matchEntity->getVenue() === null) {
            throw new InvalidMatchException("Invalid match venueId:{$matchEntity->getGroupId()}");
        }

        $statusType = $matchEntity->getStatus()->getType();

        if ($matchEntity->getFinishedAt() !== null && !MatchStatusType::FINISHED()->equals($statusType)) {
            throw new InvalidMatchException("finshed_at can only be set if the match_status is FINISHED");
        }

        $phaseStatusCodes = ['1st_half', '2nd_half', 'extra_time_1st_half', 'extra_time_2nd_half'];
        if ($matchEntity->getPhaseStartedAt() === null && in_array($matchEntity->getStatus()->getCode(), $phaseStatusCodes)) {
            throw new InvalidMatchException("phaseStartedAt must be set if the match is in:" . implode(',', $phaseStatusCodes));
        }

        $scoreStatus = [MatchStatusType::FINISHED()->getKey(), MatchStatusType::INTERRUPTED()->getKey(), MatchStatusType::LIVE()->getKey()];
        if ($matchEntity->getScore() === null && in_array($statusType->getKey(), $scoreStatus)) {
            throw new InvalidMatchException("score must be set if match is in:" . implode(',', $scoreStatus));
        }

        $noScoreStatusTypes = [MatchStatusType::NOT_STARTED()->getKey(), MatchStatusType::CANCELLED()->getKey()];
        if ($matchEntity->getScore() !== null && in_array($statusType->getKey(), $noScoreStatusTypes)) {
            throw new InvalidMatchException("score can not be set if status type is in:" . implode(',', $noScoreStatusTypes));
        }
    }
}