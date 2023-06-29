<?php


namespace Sportal\FootballApi\Domain\Stage;


interface IStageModel
{

    public function setStages(array $stageEntities): IStageModel;

    public function getStages(): array;

    public function withBlacklist(): IStageModel;

    public function upsert(): IStageModel;

    public function delete(): void;

}