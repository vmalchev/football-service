<?php


namespace Sportal\FootballApi\Domain\Stage;


use DateTimeInterface;
use Sportal\FootballApi\Domain\Match\MatchCoverage;
use Sportal\FootballApi\Domain\Season\ISeasonEntity;

interface IStageEntityFactory
{
    public function setEmpty(): IStageEntityFactory;

    public function setFrom(IStageEntity $stage): IStageEntityFactory;

    public function setId(?string $id): IStageEntityFactory;

    public function setName(string $name): IStageEntityFactory;

    public function setType(?StageType $type): IStageEntityFactory;

    public function setSeasonId(string $seasonId): IStageEntityFactory;

    public function setSeason(?ISeasonEntity $season): IStageEntityFactory;

    public function setStartDate(?DateTimeInterface $startDate): IStageEntityFactory;

    public function setEndDate(?DateTimeInterface $endDate): IStageEntityFactory;

    public function setConfederation(?string $confederation): IStageEntityFactory;

    public function create(): IStageEntity;

    public function setCoverage(?MatchCoverage $coverage): IStageEntityFactory;

    public function setOrderInSeason(?int $orderInSeason): IStageEntityFactory;

    public function setStageStatus(?StageStatus $stageStatus): IStageEntityFactory;
}