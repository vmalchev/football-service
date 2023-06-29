<?php


namespace Sportal\FootballApi\Application\Team\Service;

use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\Team\Input;
use Sportal\FootballApi\Application\Team\Output;
use Sportal\FootballApi\Domain\Team\ITeamProfileBuilder;
use Sportal\FootballApi\Domain\Team\ITeamRepository;
use Sportal\FootballApi\Infrastructure\AOP\AttachAssets;


class Get implements IService
{
    /**
     * @var Output\Profile\Mapper
     */
    private Output\Profile\Mapper $outputMapper;

    /**
     * @var ITeamProfileBuilder
     */
    private ITeamProfileBuilder $teamProfileBuilder;

    /**
     * @var ITeamRepository
     */
    private ITeamRepository $teamRepository;

    /**
     * @param Output\Profile\Mapper $outputMapper
     * @param ITeamProfileBuilder $teamProfileBuilder
     * @param ITeamRepository $teamRepository
     */
    public function __construct(
        Output\Profile\Mapper $outputMapper,
        ITeamProfileBuilder $teamProfileBuilder,
        ITeamRepository $teamRepository
    ) {
        $this->outputMapper = $outputMapper;
        $this->teamProfileBuilder = $teamProfileBuilder;
        $this->teamRepository = $teamRepository;
    }

    /**
     * @AttachAssets
     * @param Input\Profile\Dto $inputDto
     * @return Output\Profile\Dto
     * @throws NoSuchEntityException
     */
    public function process(IDto $inputDto): Output\Profile\Dto
    {
        $teamEntity = $this->teamRepository->findById($inputDto->getId());

        if (is_null($teamEntity)) {
            throw new NoSuchEntityException($inputDto->getId());
        }

        $teamProfile = $this->teamProfileBuilder->build($teamEntity);
        return $this->outputMapper->map($teamProfile);
    }
}