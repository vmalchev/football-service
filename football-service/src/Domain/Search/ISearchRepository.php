<?php


namespace Sportal\FootballApi\Domain\Search;


use Sportal\FootballApi\Application\IDto;

interface ISearchRepository
{
    public function searchBy(IDto $queryDto);
}