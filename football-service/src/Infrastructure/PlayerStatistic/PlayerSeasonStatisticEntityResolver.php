<?php


namespace Sportal\FootballApi\Infrastructure\PlayerStatistic;


use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Domain\Player\IPlayerRepository;
use Sportal\FootballApi\Domain\PlayerStatistic\PlayerSeasonStatisticFilter;
use Sportal\FootballApi\Domain\Season\ISeasonRepository;
use Sportal\FootballApi\Domain\Team\ITeamRepository;

class PlayerSeasonStatisticEntityResolver
{
    /**
     * @var IPlayerRepository
     */
    private IPlayerRepository $playerRepository;

    /**
     * @var ITeamRepository
     */
    private ITeamRepository $teamRepository;

    /**
     * @var ISeasonRepository
     */
    private ISeasonRepository $seasonRepository;

    /**
     * @param IPlayerRepository $playerRepository
     * @param ITeamRepository $teamRepository
     * @param ISeasonRepository $seasonRepository
     */
    public function __construct(
        IPlayerRepository $playerRepository,
        ITeamRepository $teamRepository,
        ISeasonRepository $seasonRepository
    )
    {
        $this->playerRepository = $playerRepository;
        $this->teamRepository = $teamRepository;
        $this->seasonRepository = $seasonRepository;
    }

    public function resolve(PlayerSeasonStatisticFilter $filter) {
        foreach ($filter->getPlayerIds() as $playerId) {
            if (!$this->playerRepository->exists($playerId)) {
                throw new NoSuchEntityException('player ' . $playerId);
            }
        }

        foreach ($filter->getSeasonIds() as $seasonId) {
            if (!$this->seasonRepository->exists($seasonId)) {
                throw new NoSuchEntityException('season ' . $seasonId);
            }
        }

        if ($filter->getTeamId() && !$this->teamRepository->exists($filter->getTeamId())) {
            throw new NoSuchEntityException('team ' . $filter->getTeamId());
        }
    }
}