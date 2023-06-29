<?php

namespace Sportal\FootballApi\Service\Event;


use Sportal\FootballApi\Dto\IDto;
use Sportal\FootballApi\Filter\MatchListingFilter;
use Sportal\FootballApi\Repository\EventRepository;
use Sportal\FootballApi\Repository\TournamentOrderRepository;
use Sportal\FootballApi\Service\IService;

class EventList implements IService
{
    /**
     * @var TournamentOrderRepository
     */
    private $tournamentOrderRepository;

    /**
     * @var EventRepository
     */
    private $eventRepository;

    public function __construct(
        TournamentOrderRepository $tournamentOrderRepository,
        EventRepository $eventRepository
    )
    {
        $this->tournamentOrderRepository = $tournamentOrderRepository;
        $this->eventRepository = $eventRepository;
    }

    public function process(IDto $inputDto)
    {
        if (empty($inputDto->getTournamentIds())) {
            $tournamentIds = array_keys($this->tournamentOrderRepository->getClientMap($inputDto->getEventOrder()));
        } else {
            $tournamentIds = $inputDto->getTournamentIds();
        }

        $eventFilter = new MatchListingFilter(
            $inputDto->getMatchIds(),
            $inputDto->getFromTime(),
            $inputDto->getToTime(),
            null,
            $inputDto->getTeamId(),
            $tournamentIds,
            $inputDto->getSeasonIds(),
            $inputDto->getStatusTypes()
        );

        $events = $this->eventRepository->getMatches($eventFilter);
        return $events;
    }
}