<?php


namespace Sportal\FootballApi\Application\MatchEvent\Service;

use Sportal\FootballApi\Application\AOP\AttachEventNotification;
use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\MatchEvent\Input;
use Sportal\FootballApi\Application\MatchEvent\Output;
use Sportal\FootballApi\Domain\EventNotification\EventNotificationEntityType;
use Sportal\FootballApi\Domain\EventNotification\EventNotificationOperationType;
use Sportal\FootballApi\Domain\Match\IMatchRepository;
use Sportal\FootballApi\Domain\MatchEvent\Exception\InvalidMatchEventException;
use Sportal\FootballApi\Domain\MatchEvent\IMatchEventCollectionBuilder;
use Sportal\FootballApi\Domain\MatchEvent\IMatchEventRepository;
use Sportal\FootballApi\Infrastructure\AOP\AttachAssets;


class Put implements IService
{
    private IMatchRepository $matchRepository;

    private IMatchEventRepository $matchEventRepository;

    private IMatchEventCollectionBuilder $collectionBuilder;

    private Input\Put\Mapper $inputMapper;

    private Output\Profile\Mapper $outputMapper;

    /**
     * Put constructor.
     * @param IMatchRepository $matchRepository
     * @param IMatchEventRepository $matchEventRepository
     * @param IMatchEventCollectionBuilder $collectionBuilder
     * @param Input\Put\Mapper $inputMapper
     * @param Output\Profile\Mapper $outputMapper
     */
    public function __construct(IMatchRepository $matchRepository,
                                IMatchEventRepository $matchEventRepository,
                                IMatchEventCollectionBuilder $collectionBuilder,
                                Input\Put\Mapper $inputMapper,
                                Output\Profile\Mapper $outputMapper)
    {
        $this->matchRepository = $matchRepository;
        $this->matchEventRepository = $matchEventRepository;
        $this->collectionBuilder = $collectionBuilder;
        $this->inputMapper = $inputMapper;
        $this->outputMapper = $outputMapper;
    }

    /**
     * @AttachAssets
     * @AttachEventNotification(object=EventNotificationEntityType::MATCH_EVENT,operation=EventNotificationOperationType::UPDATE)
     * @param IDto $inputDto
     * @return Output\Profile\Dto
     * @throws NoSuchEntityException|InvalidMatchEventException
     */
    public function process(IDto $inputDto): Output\Profile\Dto
    {
        /** @var Input\Put\Dto $inputDto */
        $matchEntity = $this->matchRepository->findById($inputDto->getMatchId());
        if ($matchEntity === null) {
            throw new NoSuchEntityException();
        }
        $matchEvents = $this->inputMapper->map($inputDto);
        return $this->outputMapper->map($inputDto->getMatchId(), $this->collectionBuilder->build($matchEntity, $matchEvents)
            ->withBlacklist()
            ->upsert()
            ->getEvents());
    }
}