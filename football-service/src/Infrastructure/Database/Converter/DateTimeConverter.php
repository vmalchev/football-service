<?php


namespace Sportal\FootballApi\Infrastructure\Database\Converter;


use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

class DateTimeConverter
{
    public static function toValue(?DateTimeInterface $dateTime): ?string
    {
        if ($dateTime !== null) {
            return (new DateTime($dateTime->format(DateTimeInterface::ATOM)))
                ->setTimezone(new DateTimeZone('UTC'))
                ->format("Y-m-d H:i:s");
        } else {
            return null;
        }
    }

    public static function fromValue(?string $value): ?DateTimeInterface
    {
        if ($value !== null) {
            return new DateTimeImmutable($value, new DateTimeZone("UTC"));
        } else {
            return null;
        }
    }
}