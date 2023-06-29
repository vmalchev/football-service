<?php

namespace Sportal\FootballApi\Application\Match\Service;

use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\Match\Input\ListLivescore\Dto;
use Sportal\FootballApi\Application\Match\Output\ListLivescore\Mapper;
use Sportal\FootballApi\Domain\Match\IMatchFilterBuilder;
use Sportal\FootballApi\Domain\Match\IMatchProfileBuilder;
use Sportal\FootballApi\Domain\Match\IMatchRepository;
use Sportal\FootballApi\Domain\Match\LivescoreMatchFilterValidator;
use Sportal\FootballApi\Infrastructure\AOP\AttachAssets;
use Sportal\FootballApi\Domain\Match\LivescoreMatchSelectionResolver;

class ListLivescore implements IService
{

    private IMatchRepository $matchRepository;

    private IMatchProfileBuilder $matchModelBuilder;

    private Mapper $mapper;

    private IMatchFilterBuilder $filterBuilder;

    private LivescoreMatchFilterValidator $filterValidator;

    private LivescoreMatchSelectionResolver $livescoreMatchSelectionResolver;

    /**
     * @param IMatchRepository $matchRepository
     * @param IMatchProfileBuilder $matchModelBuilder
     * @param Mapper $mapper
     * @param LivescoreMatchFilterValidator $filterValidator
     * @param IMatchFilterBuilder $filterBuilder
     * @param LivescoreMatchSelectionResolver $livescoreMatchSelectionResolver
     */
    public function __construct(IMatchRepository              $matchRepository,
                                IMatchProfileBuilder          $matchModelBuilder,
                                Mapper                        $mapper,
                                LivescoreMatchFilterValidator $filterValidator,
                                IMatchFilterBuilder           $filterBuilder,
                                LivescoreMatchSelectionResolver $livescoreMatchSelectionResolver)
    {
        $this->matchRepository = $matchRepository;
        $this->matchModelBuilder = $matchModelBuilder;
        $this->mapper = $mapper;
        $this->filterValidator = $filterValidator;
        $this->filterBuilder = $filterBuilder;
        $this->livescoreMatchSelectionResolver = $livescoreMatchSelectionResolver;
    }

    /**
     * @param IDto $inputDto
     * @return \Sportal\FootballApi\Application\Match\Output\ListLivescore\Dto
     * @throws NoSuchEntityException
     * @AttachAssets
     */
    public function process(IDto $inputDto)
    {
        /**
         * @var $inputDto Dto
         */

        $fromKickoffTime = null;
        $toKickoffTime = null;
        $matchIds = $this->livescoreMatchSelectionResolver->resolve($inputDto);
        if (!is_null($inputDto->getDate()) && empty($matchIds)) {
            $utcSign = $inputDto->getUtcOffset() < 0 ? '-' : '+';

            $fromKickoffTime =  new \DateTimeImmutable(
                $inputDto->getDate()->format('Y-m-d') .
                'T00:00:00' .
                $utcSign . gmdate("H:i", abs($inputDto->getUtcOffset()) * 3600)
            );

            $toKickoffTime = $fromKickoffTime->add(new \DateInterval('PT23H59M59S'));
        }

        $matchFilter = $this->filterBuilder
            ->setTournamentGroup($inputDto->getTournamentGroup())
            ->setMatchIds($matchIds)
            ->setFromKickoffTime($fromKickoffTime)
            ->setToKickoffTime($toKickoffTime)
            ->setStatusTypes($inputDto->getStatusTypes())
            ->create();

        $this->filterValidator->validate($matchFilter);

        $matches = array_map([$this->matchModelBuilder, 'build'], $this->matchRepository->findByFilter($matchFilter));

        return $this->mapper->map($matches);
    }
}