<?php


namespace Sportal\FootballApi\Infrastructure\Database\Converter;


use DateTimeImmutable;
use DateTimeInterface;

class DateConverter
{
    public static function fromValue($value): ?DateTimeInterface
    {
        if ($value !== null) {
            return new DateTimeImmutable($value);
        } else {
            return null;
        }
    }

    public static function toValue(?DateTimeInterface $dateTime): ?string
    {
        return $dateTime !== null ? $dateTime->format("Y-m-d") : null;
    }
}