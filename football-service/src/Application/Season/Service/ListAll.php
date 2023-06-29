<?php


namespace Sportal\FootballApi\Application\Season\Service;


use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\Season\Input;
use Sportal\FootballApi\Application\Season\Output;
use Sportal\FootballApi\Domain\Season\ISeasonRepository;
use Sportal\FootballApi\Domain\Season\SeasonFilter;
use Sportal\FootballApi\Domain\Team\ITeamRepository;
use Sportal\FootballApi\Domain\Tournament\ITournamentRepository;
use Sportal\FootballApi\Infrastructure\AOP\AttachAssets;


final class ListAll implements IService
{
    private ISeasonRepository $seasonRepository;

    private ITournamentRepository $tournamentRepository;

    private ITeamRepository $teamRepository;

    private Output\ListAll\Mapper $outputMapper;

    /**
     * @param ISeasonRepository $seasonRepository
     * @param ITournamentRepository $tournamentRepository
     * @param ITeamRepository $teamRepository
     * @param Output\ListAll\Mapper $outputMapper
     */
    public function __construct(
        ISeasonRepository $seasonRepository,
        ITournamentRepository $tournamentRepository,
        ITeamRepository $teamRepository,
        Output\ListAll\Mapper $outputMapper
    ) {
        $this->seasonRepository = $seasonRepository;
        $this->tournamentRepository = $tournamentRepository;
        $this->teamRepository = $teamRepository;
        $this->outputMapper = $outputMapper;
    }


    /**
     * @AttachAssets
     * @param IDto $inputDto
     * @return Output\ListAll\Dto
     * @throws NoSuchEntityException
     */
    public function process(IDto $inputDto): Output\ListAll\Dto
    {
        if (!is_null($inputDto->getTournamentId()) &&
            $this->tournamentRepository->exists($inputDto->getTournamentId())  == false
        ) {
            throw new NoSuchEntityException($inputDto->getTournamentId() . ' tournament_id');
        }

        if (!is_null($inputDto->getTeamId()) &&
            $this->teamRepository->exists($inputDto->getTeamId()) == false
        ) {
            throw new NoSuchEntityException($inputDto->getTeamId() . ' team_id');
        }

        $seasonFilter = SeasonFilter::create()
            ->setTournamentId($inputDto->getTournamentId())
            ->setTeamId($inputDto->getTeamId())
            ->setStatus($inputDto->getStatus());

        $seasonEntities = $this->seasonRepository->listByFilter($seasonFilter);

        return $this->outputMapper->map($seasonEntities);
    }
}