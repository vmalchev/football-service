<?php


namespace Sportal\FootballApi\Application\Match\Service;


use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\Match;
use Sportal\FootballApi\Domain\Match\IMatchProfileBuilder;
use Sportal\FootballApi\Domain\Match\IMatchRepository;
use Sportal\FootballApi\Infrastructure\AOP\AttachAssets;


class Get implements IService
{
    private IMatchRepository $matchRepository;

    private IMatchProfileBuilder $matchModelBuilder;

    private Match\Output\Get\Mapper $mapper;

    /**
     * Get constructor.
     * @param IMatchRepository $matchRepository
     * @param Match\Output\Get\Mapper $mapper
     * @param IMatchProfileBuilder $matchModelBuilder
     */
    public function __construct(IMatchRepository $matchRepository, Match\Output\Get\Mapper $mapper, IMatchProfileBuilder $matchModelBuilder)
    {
        $this->matchRepository = $matchRepository;
        $this->mapper = $mapper;
        $this->matchModelBuilder = $matchModelBuilder;
    }

    /**
     * @AttachAssets
     * @param IDto $inputDto
     * @return Match\Output\Get\Dto
     * @throws NoSuchEntityException
     */
    public function process(IDto $inputDto): Match\Output\Get\Dto
    {
        /** @var $inputDto Match\Input\Get\Dto */
        $match = $this->matchRepository->findById($inputDto->getMatchId());
        if ($match === null) {
            throw new NoSuchEntityException();
        }
        return $this->mapper->map($this->matchModelBuilder->build($match));
    }
}