<?php


namespace Sportal\FootballApi\Domain\Match;


use DateTime;
use DateTimeInterface;

class MatchMinuteResolver
{
    const FIRST_HALF = '1st_half';
    const SECOND_HALF = '2nd_half';
    const EXTRA_TIME_FIRST_HALF = 'extra_time_1st_half';
    const EXTRA_TIME_SECOND_HALF = 'extra_time_2nd_half';

    const PHASE_DURATION = [
        self::FIRST_HALF => 45,
        self::SECOND_HALF => 45,
        self::EXTRA_TIME_FIRST_HALF => 15,
        self::EXTRA_TIME_SECOND_HALF => 15
    ];

    const PHASE_START = [
        self::FIRST_HALF => 0,
        self::SECOND_HALF => 45,
        self::EXTRA_TIME_FIRST_HALF => 90,
        self::EXTRA_TIME_SECOND_HALF => 105
    ];

    public function resolve(string $statusCode, ?DateTimeInterface $phaseStartedAt): ?MatchMinute
    {
        $now = new DateTime();
        if (isset(self::PHASE_START[$statusCode]) && $phaseStartedAt !== null && $now >= $phaseStartedAt) {
            $minute = floor(($now->getTimestamp() - $phaseStartedAt->getTimestamp()) / 60) + 1;
            $startsAt = self::PHASE_START[$statusCode];
            $length = self::PHASE_DURATION[$statusCode];
            $injuryMinute = null;
            if ($minute > $length) {
                $injuryMinute = $minute - $length;
                $minute = $startsAt + $length;
            } else {
                $minute += $startsAt;
            }
            return new MatchMinute($minute, $injuryMinute);
        }
        return null;
    }
}