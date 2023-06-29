<?php

namespace Sportal\FootballApi\Domain\Player;


interface IPlayerProfile
{
    public function getHeight(): ?int;

    public function getWeight(): ?int;
}