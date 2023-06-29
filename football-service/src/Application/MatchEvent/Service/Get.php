<?php


namespace Sportal\FootballApi\Application\MatchEvent\Service;


use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\MatchEvent\Input;
use Sportal\FootballApi\Application\MatchEvent\Output;
use Sportal\FootballApi\Domain\Match\IMatchRepository;
use Sportal\FootballApi\Domain\MatchEvent\IMatchEventRepository;
use Sportal\FootballApi\Infrastructure\AOP\AttachAssets;

class Get implements IService
{
    private IMatchEventRepository $matchEventRepository;
    private IMatchRepository $matchRepository;
    private Output\Profile\Mapper $outputMapper;

    /**
     * Get constructor.
     * @param IMatchEventRepository $matchEventRepository
     * @param Output\Profile\Mapper $outputMapper
     * @param IMatchRepository $matchRepository
     */
    public function __construct(IMatchEventRepository $matchEventRepository,
                                Output\Profile\Mapper $outputMapper,
                                IMatchRepository $matchRepository)
    {
        $this->matchEventRepository = $matchEventRepository;
        $this->outputMapper = $outputMapper;
        $this->matchRepository = $matchRepository;
    }


    /**
     * @AttachAssets
     * @param IDto $inputDto
     * @return Output\Profile\Dto
     * @throws NoSuchEntityException
     */
    public function process(IDto $inputDto): Output\Profile\Dto
    {
        /** @var Input\Get\Dto $inputDto */
        if (!$this->matchRepository->existsById($inputDto->getMatchId())) {
            throw new NoSuchEntityException();
        }
        $matchEventEntity = $this->matchEventRepository->findByMatchId($inputDto->getMatchId());
        return $this->outputMapper->map($inputDto->getMatchId(), $matchEventEntity);
    }
}