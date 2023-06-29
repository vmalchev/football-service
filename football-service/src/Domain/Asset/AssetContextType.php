<?php


namespace Sportal\FootballApi\Domain\Asset;


use MyCLabs\Enum\Enum;

class AssetContextType extends Enum
{
    const TEAM = 'team';
    const SEASON = 'tournament_season';
}