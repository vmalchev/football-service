<?php


namespace Sportal\FootballApi\Domain\Stage;


use DateTimeInterface;
use Sportal\FootballApi\Domain\Match\MatchCoverage;
use Sportal\FootballApi\Domain\Season\ISeasonEntity;

interface IStageEntity
{
    public function getId(): ?string;

    public function getName(): string;

    public function getType(): ?StageType;

    public function getSeasonId(): string;

    public function getSeason(): ?ISeasonEntity;

    public function getStartDate(): ?DateTimeInterface;

    public function getEndDate(): ?DateTimeInterface;

    public function getConfederation(): ?string;

    public function getOrderInSeason(): ?int;

    public function getCoverage(): ?MatchCoverage;

    public function getStageStatus(): ?StageStatus;

    public function setStageStatus(StageStatus $stageStatus): void;
}