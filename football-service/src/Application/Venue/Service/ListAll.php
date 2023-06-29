<?php


namespace Sportal\FootballApi\Application\Venue\Service;


use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\Venue\Input;
use Sportal\FootballApi\Application\Venue\Input\ListAll\Mapper;
use Sportal\FootballApi\Application\Venue\Output;
use Sportal\FootballApi\Domain\Venue\IVenueRepository;
use Sportal\FootballApi\Infrastructure\AOP\AttachAssets;


final class ListAll implements IService
{
    private IVenueRepository $venueRepository;

    private Output\ListAll\Mapper $outputMapper;

    private Mapper $inputMapper;

    /**
     * @param IVenueRepository $venueRepository
     * @param Output\ListAll\Mapper $outputMapper
     * @param Mapper $inputMapper
     */
    public function __construct(IVenueRepository $venueRepository,
                                Output\ListAll\Mapper $outputMapper,
                                Mapper $inputMapper)
    {
        $this->venueRepository = $venueRepository;
        $this->outputMapper = $outputMapper;
        $this->inputMapper = $inputMapper;
    }


    /**
     * @AttachAssets
     * @param IDto $inputDto
     * @return Output\ListAll\Dto
     */
    public function process(IDto $inputDto): Output\ListAll\Dto
    {
        /** @var Input\ListAll\Dto $inputDto */
        $venues = $this->venueRepository->findAll($this->inputMapper->map($inputDto));
        $venueDtoArray = array_map([$this->outputMapper, 'map'], $venues);
        return new Output\ListAll\Dto($venueDtoArray);
    }
}