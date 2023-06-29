<?php


namespace Sportal\FootballApi\Application\Lineup\Services;


use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\Lineup;
use Sportal\FootballApi\Domain\Lineup\ILineupProfileBuilder;
use Sportal\FootballApi\Domain\Match\IMatchRepository;
use Sportal\FootballApi\Infrastructure\AOP\AttachAssets;

class Profile implements IService
{
    private ILineupProfileBuilder $profileBuilder;
    private Lineup\Output\Profile\Mapper $outputMapper;
    private IMatchRepository $matchRepository;

    /**
     * Get constructor.
     * @param Lineup\Output\Profile\Mapper $outputMapper
     * @param IMatchRepository $matchRepository
     * @param ILineupProfileBuilder $profileBuilder
     */
    public function __construct(Lineup\Output\Profile\Mapper $outputMapper,
                                IMatchRepository $matchRepository,
                                ILineupProfileBuilder $profileBuilder)
    {
        $this->outputMapper = $outputMapper;
        $this->matchRepository = $matchRepository;
        $this->profileBuilder = $profileBuilder;
    }


    /**
     * @AttachAssets
     * @param IDto $inputDto
     * @return Lineup\Output\Profile\Dto
     * @throws NoSuchEntityException
     */
    public function process(IDto $inputDto): Lineup\Output\Profile\Dto
    {
        /** @var Lineup\Input\Profile\Dto $inputDto */
        if (!$this->matchRepository->existsById($inputDto->getId())) {
            throw new NoSuchEntityException($inputDto->getId());
        }
        $profile = $this->profileBuilder->build($inputDto->getId());
        if (is_null($profile)) {
            return new Lineup\Output\Profile\Dto($inputDto->getId(), Lineup\Output\Profile\Status::NOT_AVAILABLE()->getKey());
        } else {
            return $this->outputMapper->map($profile);
        }
    }
}