<?php


namespace Sportal\FootballApi\Domain\Player;


use Sportal\FootballApi\Application\Player\Dto\PlayerEditSocialDto;
use Sportal\FootballApi\Infrastructure\Player\PlayerSocialEntity;

interface IPlayerSocialEntity
{
    public static function fromPlayerSocialDto(PlayerEditSocialDto $playerSocialDto): PlayerSocialEntity;
}