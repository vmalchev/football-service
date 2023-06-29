<?php

namespace Sportal\FootballApi\Application\Season\Service;

use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\Season\Input\Details\Dto;
use Sportal\FootballApi\Application\Season\Output\Details\Mapper;
use Sportal\FootballApi\Domain\Season\ISeasonDetailsBuilder;
use Sportal\FootballApi\Domain\Season\SeasonFilter;
use Sportal\FootballApi\Infrastructure\AOP\AttachAssets;

class Details implements IService
{
    private ISeasonDetailsBuilder $detailsBuilder;

    private Mapper $mapper;

    /**
     * @param ISeasonDetailsBuilder $detailsBuilder
     * @param Mapper $mapper
     */
    public function __construct(ISeasonDetailsBuilder $detailsBuilder, Mapper $mapper)
    {
        $this->detailsBuilder = $detailsBuilder;
        $this->mapper = $mapper;
    }

    /**
     * @param IDto $inputDto
     * @return \Sportal\FootballApi\Application\Season\Output\Details\Dto
     * @throws NoSuchEntityException
     * @AttachAssets
     */
    public function process(IDto $inputDto): \Sportal\FootballApi\Application\Season\Output\Details\Dto
    {
        /**
         * @var Dto $inputDto
         */
        $seasonFilter = SeasonFilter::create()
            ->setSeasonId($inputDto->getSeasonId())
            ->setTournamentId($inputDto->getTournamentId())
            ->setStatus($inputDto->getStatus());

        return $this->mapper->map($this->detailsBuilder->build($seasonFilter));
    }
}