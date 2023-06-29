<?php

namespace Sportal\FootballApi\Domain\Season;


class SeasonDetails implements ISeasonDetails
{

    private ISeasonEntity $season;

    private array $stages;

    private array $rounds;

    public function setSeason(ISeasonEntity $seasonEntity): ISeasonDetails
    {
        $details = clone $this;
        $details->season = $seasonEntity;
        return $details;
    }

    public function getSeason(): ISeasonEntity
    {
        return $this->season;
    }

    public function setStages(array $stageEntities): ISeasonDetails
    {
        $details = clone $this;
        $details->stages = $stageEntities;
        return $details;
    }

    public function getStages(): array
    {
        return $this->stages;
    }

    public function setRounds(array $rounds): ISeasonDetails
    {
        $details = clone $this;
        $details->rounds = $rounds;
        return $details;
    }

    public function getRounds(string $stageId): array
    {
        return $this->rounds[$stageId];
    }
}