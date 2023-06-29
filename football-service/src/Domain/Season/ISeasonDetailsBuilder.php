<?php

namespace Sportal\FootballApi\Domain\Season;

use Sportal\FootballApi\Application\Exception\NoSuchEntityException;

interface ISeasonDetailsBuilder
{

    /**
     * @param SeasonFilter $filter
     * @throws NoSuchEntityException
     * @throws \InvalidArgumentException
     * @return ISeasonDetails
     */
    public function build(SeasonFilter $filter): ISeasonDetails;
}