<?php


namespace Sportal\FootballApi\Domain\LineupPlayerType;


interface ILineupPlayerTypeEntity
{
    public function getId(): ?string;

    public function getName(): string;

    public function getCategory(): string;

    public function getCode(): string;

    public function getSortOrder(): int;
}