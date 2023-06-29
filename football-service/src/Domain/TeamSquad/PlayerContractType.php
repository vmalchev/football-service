<?php


namespace Sportal\FootballApi\Domain\TeamSquad;


use MyCLabs\Enum\Enum;

/**
 * @method static PlayerContractType PERMANENT()
 * @method static PlayerContractType LOAN()
 * @method static PlayerContractType UNKNOWN()
 */
class PlayerContractType extends Enum
{
    const PERMANENT = 'PERMANENT';
    const LOAN = 'LOAN';
    const UNKNOWN = 'UNKNOWN';

    /**
     * @param string|null $key
     * @return PlayerContractType
     */
    public static function forKey(?string $key): PlayerContractType
    {
        return !is_null($key) ? new PlayerContractType($key) : PlayerContractType::UNKNOWN();
    }
}