<?php


namespace Sportal\FootballApi\Domain\Asset;


use MyCLabs\Enum\Enum;

/**
 * @method static AssetEntityType TEAM()
 * @method static AssetEntityType PLAYER()
 * @method static AssetEntityType COACH()
 * @method static AssetEntityType VENUE()
 * @method static AssetEntityType COUNTRY()
 * @method static AssetEntityType REFEREE()
 * @method static AssetEntityType PRESIDENT()
 * @method static AssetEntityType TOURNAMENT()
 * @method static AssetEntityType ODD_PROVIDER()
 */
class AssetEntityType extends Enum
{
    const TEAM = 'team';
    const PLAYER = 'player';
    const COACH = 'coach';
    const VENUE = 'venue';
    const COUNTRY = 'country';
    const REFEREE = 'referee';
    const PRESIDENT = 'president';
    const TOURNAMENT = 'tournament';
    const ODD_PROVIDER = 'odd_provider';
}
