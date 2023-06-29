<?php


namespace Sportal\FootballApi\Application\TeamSquad\Service;


use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\TeamSquad\Input;
use Sportal\FootballApi\Application\TeamSquad\Output;
use Sportal\FootballApi\Domain\Team\ITeamRepository;
use Sportal\FootballApi\Domain\TeamSquad\TeamSquadBuilder;
use Sportal\FootballApi\Infrastructure\AOP\AttachAssets;


class Get implements IService
{
    private TeamSquadBuilder $teamSquadBuilder;
    private ITeamRepository $teamRepository;
    private Input\Get\StatusMapper $statusMapper;
    private Output\Get\Mapper $outputMapper;

    /**
     * Get constructor.
     * @param \Sportal\FootballApi\Domain\TeamSquad\TeamSquadBuilder $teamSquadBuilder
     * @param ITeamRepository $teamRepository
     * @param Input\Get\StatusMapper $statusMapper
     * @param \Sportal\FootballApi\Application\TeamSquad\Output\Get\Mapper $outputMapper
     */
    public function __construct(TeamSquadBuilder $teamSquadBuilder,
                                ITeamRepository $teamRepository,
                                Input\Get\StatusMapper $statusMapper,
                                Output\Get\Mapper $outputMapper)
    {
        $this->teamSquadBuilder = $teamSquadBuilder;
        $this->teamRepository = $teamRepository;
        $this->statusMapper = $statusMapper;
        $this->outputMapper = $outputMapper;
    }


    /**
     * @AttachAssets
     * @param Input\Get\Dto $inputDto
     * @return Output\Get\Dto
     * @throws NoSuchEntityException
     */
    public function process(IDto $inputDto): Output\Get\Dto
    {
        $status = $this->statusMapper->map($inputDto->getStatus());
        $teamEntity = $this->teamRepository->findById($inputDto->getTeamId());
        if ($teamEntity === null) {
            throw new NoSuchEntityException($inputDto->getTeamId());
        }
        return $this->outputMapper->map($this->teamSquadBuilder->build($teamEntity, $status));
    }
}