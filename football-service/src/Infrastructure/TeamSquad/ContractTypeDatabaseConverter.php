<?php


namespace Sportal\FootballApi\Infrastructure\TeamSquad;


use Sportal\FootballApi\Domain\TeamSquad\PlayerContractType;

class ContractTypeDatabaseConverter
{
    public static function fromValue($value): PlayerContractType
    {
        if ($value === null) {
            return PlayerContractType::UNKNOWN();
        } else if ($value) {
            return PlayerContractType::LOAN();
        } else {
            return PlayerContractType::PERMANENT();
        }
    }

    public static function toValue(?PlayerContractType $status)
    {
        if ($status->getValue() == PlayerContractType::LOAN()->getValue()) {
            return 1;
        } else if ($status->getValue() == PlayerContractType::PERMANENT()->getValue()) {
            return 0;
        } else {
            return null;
        }
    }

}