<?php


namespace Sportal\FootballApi\Domain\Team;


use Sportal\FootballApi\Application\Exception\NoSuchEntityException;

interface ITeamColorsRule
{

    /**
     * @param string $entityTypeKey
     * @param string $entityId
     * @throws NoSuchEntityException
     */
    public function validate(string $entityTypeKey, string $entityId): void;
}