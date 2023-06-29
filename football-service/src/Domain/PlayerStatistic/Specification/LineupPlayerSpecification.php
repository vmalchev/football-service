<?php


namespace Sportal\FootballApi\Domain\PlayerStatistic\Specification;


use Sportal\FootballApi\Domain\Lineup\ILineupPlayerRepository;
use Sportal\FootballApi\Domain\Lineup\ILineupRepository;
use Sportal\FootballApi\Domain\Match\IMatchRepository;
use Sportal\FootballApi\Domain\Player\IPlayerRepository;
use Sportal\FootballApi\Domain\PlayerStatistic\Exception\InvalidLineupPlayerException;
use Sportal\FootballApi\Domain\PlayerStatistic\Exception\InvalidMatchTeamException;
use Sportal\FootballApi\Domain\PlayerStatistic\Exception\InvalidPlayerStatisticEntityException;
use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerStatisticEntity;
use Sportal\FootballApi\Domain\Team\ITeamRepository;

final class LineupPlayerSpecification
{
    private IPlayerRepository $playerRepository;
    private ITeamRepository $teamRepository;
    private IMatchRepository $matchRepository;
    private ILineupRepository $lineupRepository;
    private ILineupPlayerRepository $lineupPlayerRepository;

    /**
     * @param IPlayerRepository $playerRepository
     * @param ITeamRepository $teamRepository
     * @param IMatchRepository $matchRepository
     * @param ILineupRepository $lineupRepository
     * @param ILineupPlayerRepository $lineupPlayerRepository
     */
    public function __construct(
        IPlayerRepository $playerRepository,
        ITeamRepository $teamRepository,
        IMatchRepository $matchRepository,
        ILineupRepository $lineupRepository,
        ILineupPlayerRepository $lineupPlayerRepository
    ) {
        $this->playerRepository = $playerRepository;
        $this->teamRepository = $teamRepository;
        $this->matchRepository = $matchRepository;
        $this->lineupRepository = $lineupRepository;
        $this->lineupPlayerRepository = $lineupPlayerRepository;
    }

    /**
     * @param IPlayerStatisticEntity $playerStatisticEntity
     * @throws InvalidLineupPlayerException
     * @throws InvalidMatchTeamException
     * @throws InvalidPlayerStatisticEntityException
     */
    public function validate(IPlayerStatisticEntity $playerStatisticEntity): void
    {
        if (!$this->playerRepository->exists($playerStatisticEntity->getPlayerId())) {
            throw new InvalidPlayerStatisticEntityException('player', $playerStatisticEntity->getPlayerId());
        }

        $matchEntity = $this->matchRepository->findById($playerStatisticEntity->getMatchId());

        if (is_null($matchEntity)) {
            throw new InvalidPlayerStatisticEntityException('match', $playerStatisticEntity->getMatchId());
        }

        if (!$this->teamRepository->exists($playerStatisticEntity->getTeamId())) {
            throw new InvalidPlayerStatisticEntityException('team', $playerStatisticEntity->getTeamId());
        }

        if ($matchEntity->getAwayTeamId() !== $playerStatisticEntity->getTeamId()
            && $matchEntity->getHomeTeamId() !== $playerStatisticEntity->getTeamId()
        ) {
            throw new InvalidMatchTeamException($playerStatisticEntity->getTeamId());
        }

        $lineup = $this->lineupRepository->findByMatchId($playerStatisticEntity->getMatchId());
        if (!is_null($lineup)) {
            $lineupPlayers = $this->lineupPlayerRepository->findByLineup($lineup);

            if (!empty($lineupPlayers)) {
                $lineupPlayerIds = [];
                foreach ($lineupPlayers as $lineupPlayer) {
                    $lineupPlayerIds[] = $lineupPlayer->getPlayerId();
                }

                if (!in_array($playerStatisticEntity->getPlayerId(), $lineupPlayerIds)) {
                    throw new InvalidLineupPlayerException($playerStatisticEntity->getPlayerId());
                }
            }
        }

    }
}