<?php


namespace Sportal\FootballApi\Infrastructure\Stage;


use Sportal\FootballApi\Domain\Stage\StageType;

class StageTypeDatabaseConverter
{
    public static function fromValue(array $data): StageType
    {
        if (!empty($data[StageTableMapper::FIELD_CUP])) {
            return StageType::KNOCK_OUT();
        } else {
            return !empty($data[StageTableMapper::FIELD_STAGE_GROUPS]) ? StageType::GROUP() : StageType::LEAGUE();
        }
    }
}