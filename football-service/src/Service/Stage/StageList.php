<?php
namespace Sportal\FootballApi\Service\Stage;

use Sportal\FootballApi\Dto\IDto;
use Sportal\FootballApi\Repository\v2\StageRepository;
use Sportal\FootballApi\Dto\Stage\StageListInput;
use Sportal\FootballApi\Entity\Stage;
use Sportal\FootballApi\Dto\Stage\StageDto;
use Sportal\FootballApi\Dto\Season\SeasonDto;
use Sportal\FootballApi\Dto\Tournament\TournamentDto;
use Sportal\FootballApi\Dto\Country\CountryDto;
use Sportal\FootballApi\Service\IService;

class StageList implements IService
{

    private $stageRepository;

    public function __construct(StageRepository $stageRepository)
    {
        $this->stageRepository = $stageRepository;
    }

    public function process(IDto $inputDto)
    {
        $stages = $this->stageRepository->findBy($inputDto);

        return array_map(
            function (Stage $stage) {
                $countryDto = CountryDto::create($stage->getSeason()
                    ->getTournament()
                    ->getCountry());
                $tournamentDto = TournamentDto::create($stage->getSeason()->getTournament(), $countryDto);
                return StageDto::create($stage, SeasonDto::create($stage->getSeason()), $tournamentDto);
            }, $stages);
    }
}
