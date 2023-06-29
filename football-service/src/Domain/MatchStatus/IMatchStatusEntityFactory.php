<?php


namespace Sportal\FootballApi\Domain\MatchStatus;


interface IMatchStatusEntityFactory
{
    public function setEmpty(): IMatchStatusEntityFactory;

    public function setId(?string $id): IMatchStatusEntityFactory;

    public function setName(string $name): IMatchStatusEntityFactory;

    public function setShortName(?string $shortName): IMatchStatusEntityFactory;

    public function setCode(string $code): IMatchStatusEntityFactory;

    public function setType(MatchStatusType $type): IMatchStatusEntityFactory;

    public function create(): IMatchStatusEntity;
}