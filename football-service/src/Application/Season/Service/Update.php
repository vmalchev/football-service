<?php


namespace Sportal\FootballApi\Application\Season\Service;


use Sportal\FootballApi\Application\Exception\DuplicateKeyException;
use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\Season;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Domain\Season\ISeasonEntityFactory;
use Sportal\FootballApi\Domain\Season\ISeasonModel;
use Sportal\FootballApi\Domain\Season\ISeasonRepository;

class Update implements IService
{

    private ISeasonRepository $seasonRepository;

    private ISeasonModel $seasonModel;

    private Season\Output\Get\Mapper $outputMapper;

    private ISeasonEntityFactory $seasonEntityFactory;

    public function __construct(ISeasonRepository $seasonRepository,
                                Season\Output\Get\Mapper $outputMapper,
                                ISeasonEntityFactory $seasonEntityFactory,
                                ISeasonModel $seasonModel)
    {
        $this->seasonRepository = $seasonRepository;
        $this->outputMapper = $outputMapper;
        $this->seasonEntityFactory = $seasonEntityFactory;
        $this->seasonModel = $seasonModel;
    }

    public function process(IDto $inputDto): Season\Output\Get\Dto
    {
        $existingEntity = $this->seasonRepository->findById($inputDto->getId());

        if (!$existingEntity) {
            throw new NoSuchEntityException($inputDto->getId());
        }

        $seasonEntity = $this->seasonEntityFactory
            ->setFrom($existingEntity)
            ->setName($inputDto->getName())
            ->create();

        if (!empty($this->seasonRepository->findByTournamentIdAndName($seasonEntity))) {
            throw new DuplicateKeyException();
        }

        $season = $this->seasonModel
            ->setSeason($seasonEntity)
            ->withBlacklist()
            ->update()
            ->getSeason();

        return $this->outputMapper->map($season);
    }
}