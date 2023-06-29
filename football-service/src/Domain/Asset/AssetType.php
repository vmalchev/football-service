<?php


namespace Sportal\FootballApi\Domain\Asset;


use MyCLabs\Enum\Enum;


/**
 * @method static AssetType LOGO()
 * @method static AssetType THUMBNAIL()
 * @method static AssetType IMAGE()
 * @method static AssetType HOME_KIT()
 * @method static AssetType AWAY_KIT()
 * @method static AssetType FLAG()
 * @method static AssetType SQUAD_IMAGE()
 *
 */
class AssetType extends Enum
{
    const LOGO = 'logo';
    const THUMBNAIL = 'thumb';
    const IMAGE = 'image';
    const HOME_KIT = 'home_kit';
    const AWAY_KIT = 'away_kit';
    const FLAG = 'flag';
    const SQUAD_IMAGE = 'squad_image';
}