<?php
namespace Sportal\FootballApi\Dto;

final class Util
{

    public static function filterNull(array $data): array
    {
        unset($data['mlContent']);
        unset($data['langCode']);
        return array_filter($data, function ($value) {
            return $value !== null;
        });
    }
}