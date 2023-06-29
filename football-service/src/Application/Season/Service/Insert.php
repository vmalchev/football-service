<?php


namespace Sportal\FootballApi\Application\Season\Service;


use Sportal\FootballApi\Application\Exception\DuplicateKeyException;
use Sportal\FootballApi\Application\Season;
use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Domain\Season\ISeasonEntityFactory;
use Sportal\FootballApi\Domain\Season\ISeasonModel;
use Sportal\FootballApi\Domain\Season\ISeasonRepository;
use Sportal\FootballApi\Domain\Season\SeasonStatus;
use Sportal\FootballApi\Domain\Tournament\ITournamentRepository;

class Insert implements IService
{
    private ISeasonRepository $seasonRepository;

    private ISeasonModel $seasonModel;

    private ITournamentRepository $tournamentRepository;

    private Season\Output\Get\Mapper $outputMapper;

    private ISeasonEntityFactory $seasonEntityFactory;

    public function __construct(ISeasonRepository $seasonRepository,
                                ITournamentRepository $tournamentRepository,
                                Season\Output\Get\Mapper $outputMapper,
                                ISeasonEntityFactory $seasonEntityFactory,
                                ISeasonModel $seasonModel)
    {
        $this->seasonRepository = $seasonRepository;
        $this->tournamentRepository = $tournamentRepository;
        $this->outputMapper = $outputMapper;
        $this->seasonEntityFactory = $seasonEntityFactory;
        $this->seasonModel = $seasonModel;
    }

    public function process(IDto $inputDto): Season\Output\Get\Dto
    {
        $tournamentEntity = $this->tournamentRepository->findById($inputDto->getTournamentId());

        if (!$tournamentEntity) {
            throw new NoSuchEntityException($inputDto->getTournamentId());
        }

        $seasonEntity = $this->seasonEntityFactory
            ->setName($inputDto->getName())
            ->setTournament($tournamentEntity)
            ->setTournamentId($inputDto->getTournamentId())
            ->setStatus(SeasonStatus::INACTIVE())
            ->create();

        if (!empty($this->seasonRepository->findByTournamentIdAndName($seasonEntity))) {
            throw new DuplicateKeyException();
        }

        $season = $this->seasonModel
            ->setSeason($seasonEntity)
            ->withBlacklist()
            ->create()
            ->getSeason();

        return $this->outputMapper->map($season);
    }
}