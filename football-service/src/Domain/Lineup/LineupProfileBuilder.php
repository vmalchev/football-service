<?php


namespace Sportal\FootballApi\Domain\Lineup;


class LineupProfileBuilder implements ILineupProfileBuilder
{
    private ILineupProfile $lineupProfile;

    private ILineupRepository $lineupRepository;

    private ILineupPlayerRepository $lineupPlayerRepository;

    /**
     * LineupProfileBuilder constructor.
     * @param ILineupRepository $lineupRepository
     * @param ILineupPlayerRepository $lineupPlayerRepository
     * @param ILineupProfile $lineupProfile
     */
    public function __construct(ILineupRepository $lineupRepository,
                                ILineupPlayerRepository $lineupPlayerRepository,
                                ILineupProfile $lineupProfile)
    {
        $this->lineupRepository = $lineupRepository;
        $this->lineupPlayerRepository = $lineupPlayerRepository;
        $this->lineupProfile = $lineupProfile;
    }

    public function build(string $matchId): ?ILineupProfile
    {
        $lineup = $this->lineupRepository->findByMatchId($matchId);
        if (!is_null($lineup)) {
            $players = $this->lineupPlayerRepository->findByLineup($lineup);
            return $this->lineupProfile->setLineup($lineup)->setPlayers($players);
        } else {
            return null;
        }
    }
}