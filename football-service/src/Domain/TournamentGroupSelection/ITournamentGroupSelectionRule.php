<?php

namespace Sportal\FootballApi\Domain\TournamentGroupSelection;

use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\Match\Exception\InvalidMatchInputException;
use Sportal\FootballApi\Application\TournamentGroupSelection\Input\Insert\CollectionDto;
use Sportal\FootballApi\Domain\Match\Exception\InvalidMatchException;

interface ITournamentGroupSelectionRule
{

    /**
     * @throws InvalidMatchInputException
     * @throws NoSuchEntityException
     * @throws InvalidMatchException
     * @throws \InvalidArgumentException
     */
    public function validate(CollectionDto $inputDto);
}