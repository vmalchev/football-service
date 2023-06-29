<?php


namespace Sportal\FootballApi\Domain\Season;


interface ISeasonModel
{
    public function setSeason(ISeasonEntity $seasonEntity): ISeasonModel;

    public function getSeason(): ISeasonEntity;

    public function withBlacklist(): ISeasonModel;

    public function create(): ISeasonModel;

    public function update(): ISeasonModel;

    public function updateStatus(): ISeasonModel;
}