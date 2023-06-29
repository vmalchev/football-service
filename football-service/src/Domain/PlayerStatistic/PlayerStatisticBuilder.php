<?php


namespace Sportal\FootballApi\Domain\PlayerStatistic;


use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Domain\Match\IMatchRepository;
use Sportal\FootballApi\Domain\Player\IPlayerRepository;
use Sportal\FootballApi\Domain\PlayerStatistic\Exception\InvalidPlayerStatisticEntityException;
use Sportal\FootballApi\Domain\PlayerStatistic\Specification\LineupPlayerSpecification;
use Sportal\FootballApi\Domain\PlayerStatistic\Specification\PlayerStatisticItemSpecification;
use Sportal\FootballApi\Domain\Team\ITeamRepository;

final class PlayerStatisticBuilder implements IPlayerStatisticBuilder
{
    private IPlayerStatisticModel $playerStatisticModel;

    private IPlayerRepository $playerRepository;
    private ITeamRepository $teamRepository;
    private IMatchRepository $matchRepository;
    private IPlayerStatisticRepository $repository;

    private LineupPlayerSpecification $lineupPlayerSpecification;

    /**
     * @param IPlayerStatisticModel $playerStatisticModel
     * @param IPlayerStatisticRepository $repository
     * @param IPlayerRepository $playerRepository
     * @param ITeamRepository $teamRepository
     * @param IMatchRepository $matchRepository
     * @param LineupPlayerSpecification $lineupPlayerSpecification;
     */
    public function __construct(
        IPlayerStatisticModel $playerStatisticModel,
        IPlayerStatisticRepository $repository,
        IPlayerRepository $playerRepository,
        ITeamRepository $teamRepository,
        IMatchRepository $matchRepository,
        LineupPlayerSpecification $lineupPlayerSpecification
    ) {
        $this->playerStatisticModel = $playerStatisticModel;
        $this->repository = $repository;
        $this->playerRepository = $playerRepository;
        $this->teamRepository = $teamRepository;
        $this->matchRepository = $matchRepository;
        $this->lineupPlayerSpecification = $lineupPlayerSpecification;
    }

    /**
     * @param IPlayerStatisticEntity $playerStatisticEntity
     * @return IPlayerStatisticModel
     * @throws Exception\InvalidLineupPlayerException
     * @throws Exception\InvalidMatchTeamException
     * @throws InvalidPlayerStatisticEntityException
     */
    public function build(IPlayerStatisticEntity $playerStatisticEntity): IPlayerStatisticModel
    {
        $this->lineupPlayerSpecification->validate($playerStatisticEntity);

        $playerStatisticModel = clone $this->playerStatisticModel;
        $playerStatisticModel->setPlayerStatisticEntity($playerStatisticEntity);

        return $playerStatisticModel;
    }
}