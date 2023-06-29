<?php


namespace Sportal\FootballApi\Application\Match\Service;


use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\Match;
use Sportal\FootballApi\Domain\Match\IMatchFilterBuilder;
use Sportal\FootballApi\Domain\Match\IMatchProfileBuilder;
use Sportal\FootballApi\Domain\Match\IMatchRepository;
use Sportal\FootballApi\Domain\Match\MatchFilterValidator;
use Sportal\FootballApi\Infrastructure\AOP\AttachAssets;

class ListAll implements IService
{
    private IMatchRepository $matchRepository;

    private IMatchProfileBuilder $matchModelBuilder;

    private Match\Output\ListAll\Mapper $mapper;

    private IMatchFilterBuilder $filterBuilder;

    private MatchFilterValidator $matchFilterValidator;

    /**
     * ListAll constructor.
     * @param IMatchRepository $matchRepository
     * @param IMatchProfileBuilder $matchModelBuilder
     * @param Match\Output\ListAll\Mapper $mapper
     * @param IMatchFilterBuilder $filterBuilder
     * @param MatchFilterValidator $matchFilterValidator
     */
    public function __construct(IMatchRepository            $matchRepository,
                                IMatchProfileBuilder        $matchModelBuilder,
                                Match\Output\ListAll\Mapper $mapper,
                                IMatchFilterBuilder         $filterBuilder,
                                MatchFilterValidator        $matchFilterValidator)
    {
        $this->matchRepository = $matchRepository;
        $this->matchModelBuilder = $matchModelBuilder;
        $this->mapper = $mapper;
        $this->filterBuilder = $filterBuilder;
        $this->matchFilterValidator = $matchFilterValidator;
    }


    /**
     * @AttachAssets
     * @param IDto $inputDto
     * @return Match\Output\ListAll\Dto
     * @throws NoSuchEntityException
     */
    public function process(IDto $inputDto): Match\Output\ListAll\Dto
    {
        /**
         * @var $inputDto Match\Input\ListAll\Dto
         */

        $filter = $this->filterBuilder
            ->setTournamentIds($inputDto->getTournamentIds())
            ->setSeasonIds($inputDto->getSeasonIds())
            ->setStageIds($inputDto->getStageIds())
            ->setGroupIds($inputDto->getGroupIds())
            ->setRoundIds($inputDto->getRoundIds())
            ->setFromKickoffTime($inputDto->getFromKickoffTime())
            ->setToKickoffTime($inputDto->getToKickoffTime())
            ->setTeamIds($inputDto->getTeamIds())
            ->setStatusTypes($inputDto->getStatusTypes())
            ->setStatusCodes($inputDto->getStatusCodes())
            ->setRefereeId($inputDto->getRefereeId())
            ->setVenueId($inputDto->getVenueId())
            ->setSortDirection($inputDto->getSortDirection())
            ->setRoundFilter($inputDto->getRoundFilters())
            ->create();

        $this->matchFilterValidator->validate($filter);

        $matches = array_map([$this->matchModelBuilder, 'build'], $this->matchRepository->findByFilter($filter));

        return $this->mapper->map($matches);
    }
}