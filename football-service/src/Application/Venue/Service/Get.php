<?php


namespace Sportal\FootballApi\Application\Venue\Service;


use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Domain\Venue\IVenueRepository;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\Venue\Input;
use Sportal\FootballApi\Application\Venue\Output;

use Sportal\FootballApi\Infrastructure\AOP\AttachAssets;


final class Get implements IService
{
    private IVenueRepository $venueRepository;

    private Output\Get\Mapper $outputMapper;


    /**
     * VenueService constructor.
     * @param IVenueRepository $venueRepository
     * @param Output\Get\Mapper $outputMapper
     */
    public function __construct(
        IVenueRepository $venueRepository,
        Output\Get\Mapper $outputMapper
    ) {
        $this->venueRepository = $venueRepository;
        $this->outputMapper = $outputMapper;
    }


    /**
     * @AttachAssets
     * @param Input\Profile\Dto $inputDto
     * @return Output\Get\Dto
     * @throws NoSuchEntityException
     */
    public function process(IDto $inputDto): Output\Get\Dto
    {
        $venueEntity = $this->venueRepository->findById($inputDto->getId());
        if ($venueEntity === null) {
            throw new NoSuchEntityException('venue ' . $inputDto->getId());
        }

        return $this->outputMapper->map($venueEntity);

    }
}