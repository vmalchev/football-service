<?php


namespace Sportal\FootballApi\Domain\KnockoutScheme;


use Sportal\FootballApi\Domain\Stage\IStageEntity;

interface IKnockoutSchemeRepository
{
    /**
     * @param IStageEntity $stage
     * @return IKnockoutSchemeEntity[]
     */
    public function findByStage(IStageEntity $stage): array;
}