<?php


namespace Sportal\FootballApi\Domain\Player;


interface IPlayerProfileBuilder
{

    /**
     * @param IPlayerEntity $playerEntity
     * @return IPlayerProfileEntity
     */
    public function build(IPlayerEntity $playerEntity): IPlayerProfileEntity;
}