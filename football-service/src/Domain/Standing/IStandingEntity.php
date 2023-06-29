<?php


namespace Sportal\FootballApi\Domain\Standing;


interface IStandingEntity
{
    public function getId(): ?string;

    public function getType(): StandingType;

    public function getEntityName(): StandingEntityName;

    public function getEntityId(): string;
}