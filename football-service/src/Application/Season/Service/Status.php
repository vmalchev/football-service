<?php


namespace Sportal\FootballApi\Application\Season\Service;


use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\Season\Output\Get\Dto;
use Sportal\FootballApi\Application\Season\Output\Get\Mapper;
use Sportal\FootballApi\Domain\Season\ISeasonEntityFactory;
use Sportal\FootballApi\Domain\Season\ISeasonModel;
use Sportal\FootballApi\Domain\Season\ISeasonRepository;
use Sportal\FootballApi\Domain\Season\SeasonStatus;
use Sportal\FootballApi\Domain\Tournament\ITournamentRepository;

class Status implements IService
{
    private ITournamentRepository $tournamentRepository;

    private ISeasonRepository $seasonRepository;

    private Mapper $outputMapper;

    private ISeasonEntityFactory $seasonEntityFactory;

    private ISeasonModel $seasonModel;

    public function __construct(ISeasonRepository $seasonRepository,
                                Mapper $outputMapper,
                                ISeasonEntityFactory $seasonEntityFactory,
                                ISeasonModel $seasonModel,
                                ITournamentRepository $tournamentRepository)
    {
        $this->seasonRepository = $seasonRepository;
        $this->outputMapper = $outputMapper;
        $this->seasonEntityFactory = $seasonEntityFactory;
        $this->seasonModel = $seasonModel;
        $this->tournamentRepository = $tournamentRepository;
    }

    public function process(IDto $inputDto): Dto
    {
        $tournamentEntity = $this->tournamentRepository->findById($inputDto->getTournamentId());

        if (!$tournamentEntity) {
            throw new NoSuchEntityException($inputDto->getTournamentId());
        }

        $season = $this->seasonRepository->findById($inputDto->getSeasonId());

        if (!$season) {
            throw new NoSuchEntityException($inputDto->getSeasonId());
        }

        if ($season->getTournamentId() !== $inputDto->getTournamentId()) {
            throw new \InvalidArgumentException(
                'Season ' . $season->getId() . ' not part of Tournament ' . $inputDto->getTournamentId()
            );
        }

        $seasonEntity = $this->seasonEntityFactory
            ->setFrom($season)
            ->setStatus(SeasonStatus::ACTIVE())
            ->create();

        $result = $this->seasonModel
            ->setSeason($seasonEntity)
            ->withBlacklist()
            ->updateStatus()
            ->getSeason();

        return $this->outputMapper->map($result);
    }
}