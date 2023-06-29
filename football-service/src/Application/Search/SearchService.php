<?php


namespace Sportal\FootballApi\Application\Search;


use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Domain\Search\ISearchRepository;
use Sportal\FootballApi\Infrastructure\Repository\SearchRepository;

class SearchService implements IService
{
    /**
     * @var ISearchRepository
     */
    private $searchRepository;

    public function __construct(SearchRepository $searchRepository)
    {
        $this->searchRepository= $searchRepository;
    }

    public function process(IDto $inputDto)
    {
        $entities = $this->searchRepository->searchBy($inputDto);

        return $entities;
    }
}