<?php

namespace Sportal\FootballApi\Domain\TournamentGroup;


interface ITournamentGroupEntity
{

    public function getCode(): string;

    public function getDescription(): ?string;

    public function getName(): string;
}