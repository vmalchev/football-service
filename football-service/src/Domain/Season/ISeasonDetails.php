<?php

namespace Sportal\FootballApi\Domain\Season;

use Sportal\FootballApi\Domain\Round\IRoundEntity;
use Sportal\FootballApi\Domain\Stage\IStageEntity;

interface ISeasonDetails
{

    /**
     * @param ISeasonEntity $seasonEntity
     */
    public function setSeason(ISeasonEntity $seasonEntity): ISeasonDetails;

    /**
     * @return ISeasonEntity
     */
    public function getSeason(): ISeasonEntity;

    /**
     * @param IStageEntity[] $stageEntities
     */
    public function setStages(array $stageEntities): ISeasonDetails;

    /**
     * @return IStageEntity[]
     */
    public function getStages(): array;

    /**
     * @param array
     */
    public function setRounds(array $rounds): ISeasonDetails;

    /**
     * @param string $stageId
     * @return IRoundEntity[]
     */
    public function getRounds(string $stageId): array;
}