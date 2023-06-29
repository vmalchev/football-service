<?php


namespace Sportal\FootballApi\Domain\Group;


interface IGroupEntity
{
    public function getId(): ?string;

    public function getName(): string;

    public function getStageId(): string;

    public function getSortorder(): ?int;
}