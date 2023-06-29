<?php


namespace Sportal\FootballApi\Domain\MatchStatus;


interface IMatchStatusEntity
{
    public function getId(): ?string;

    public function getName(): string;

    public function getShortName(): ?string;

    public function getCode(): string;

    public function getType(): MatchStatusType;
}