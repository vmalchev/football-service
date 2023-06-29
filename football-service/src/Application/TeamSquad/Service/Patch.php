<?php


namespace Sportal\FootballApi\Application\TeamSquad\Service;


use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\TeamSquad\Input;
use Sportal\FootballApi\Application\TeamSquad\Output;
use Sportal\FootballApi\Domain\Team\ITeamRepository;
use Sportal\FootballApi\Domain\TeamSquad\Exception\InvalidTeamSquadException;
use Sportal\FootballApi\Domain\TeamSquad\TeamPlayerModelBuilder;
use Sportal\FootballApi\Domain\TeamSquad\TeamSquadBuilder;
use Sportal\FootballApi\Infrastructure\AOP\AttachAssets;


class Patch implements IService
{
    private Input\Patch\PlayerMapper $playerMapper;

    private ITeamRepository $teamRepository;

    private TeamPlayerModelBuilder $modelBuilder;

    private Output\Get\Mapper $outputMapper;

    private TeamSquadBuilder $teamSquadBuilder;

    /**
     * Patch constructor.
     * @param Input\Patch\PlayerMapper $playerMapper
     * @param \Sportal\FootballApi\Application\TeamSquad\Output\Get\Mapper $outputMapper
     * @param ITeamRepository $teamRepository
     * @param TeamSquadBuilder $teamSquadBuilder
     * @param TeamPlayerModelBuilder $modelBuilder
     */
    public function __construct(Input\Patch\PlayerMapper $playerMapper,
                                Output\Get\Mapper $outputMapper,
                                ITeamRepository $teamRepository,
                                TeamSquadBuilder $teamSquadBuilder,
                                TeamPlayerModelBuilder $modelBuilder)
    {
        $this->playerMapper = $playerMapper;
        $this->teamRepository = $teamRepository;
        $this->outputMapper = $outputMapper;
        $this->teamSquadBuilder = $teamSquadBuilder;
        $this->modelBuilder = $modelBuilder;
    }

    /**
     * @AttachAssets
     * @param IDto $inputDto
     * @return Output\Get\Dto
     * @throws NoSuchEntityException|InvalidTeamSquadException
     */
    public function process(IDto $inputDto): Output\Get\Dto
    {
        /** @var $inputDto Input\Patch\Dto */
        $team = $this->teamRepository->findById($inputDto->getTeamId());

        if ($team === null) {
            throw new NoSuchEntityException($inputDto->getTeamId());
        }

        if ($inputDto->getPlayers() !== null) {
            $teamPlayers = $this->playerMapper->map($inputDto);
            $this->modelBuilder->build($team, $teamPlayers)
                ->withBlacklist()
                ->upsert();
        }

        return $this->outputMapper->map($this->teamSquadBuilder->build($team));
    }
}