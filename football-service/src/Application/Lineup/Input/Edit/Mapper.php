<?php


namespace Sportal\FootballApi\Application\Lineup\Input\Edit;


use Sportal\FootballApi\Domain\Lineup\ILineupEntityFactory;
use Sportal\FootballApi\Domain\Lineup\ILineupPlayerEntityFactory;
use Sportal\FootballApi\Domain\Lineup\ILineupProfile;
use Sportal\FootballApi\Domain\Lineup\LineupStatus;

class Mapper
{
    private ILineupEntityFactory $lineupFactory;
    private ILineupPlayerEntityFactory $lineupPlayerFactory;
    private ILineupProfile $lineupProfile;

    /**
     * Mapper constructor.
     * @param ILineupEntityFactory $lineupFactory
     * @param ILineupPlayerEntityFactory $lineupPlayerFactory
     * @param ILineupProfile $lineupProfile
     */
    public function __construct(ILineupEntityFactory $lineupFactory, ILineupPlayerEntityFactory $lineupPlayerFactory, ILineupProfile $lineupProfile)
    {
        $this->lineupFactory = $lineupFactory;
        $this->lineupPlayerFactory = $lineupPlayerFactory;
        $this->lineupProfile = $lineupProfile;
    }

    public function map(Dto $dto): ILineupProfile
    {
        $lineup = $this->lineupFactory->setEmpty()
            ->setMatchId($dto->getMatchId())
            ->setStatus(!is_null($dto->getStatus()) ? LineupStatus::forKey($dto->getStatus()) : null)
            ->setHomeTeamFormation(!is_null($dto->getHomeTeam()) ? $dto->getHomeTeam()->getFormation() : null)
            ->setAwayTeamFormation(!is_null($dto->getAwayTeam()) ? $dto->getAwayTeam()->getFormation() : null)
            ->setHomeCoachId(!is_null($dto->getHomeTeam()) ? $dto->getHomeTeam()->getCoachId() : null)
            ->setAwayCoachId(!is_null($dto->getAwayTeam()) ? $dto->getAwayTeam()->getCoachId() : null)
            ->create();

        $homePlayers = $this->mapPlayers($dto->getHomeTeam(), true, $dto->getMatchId());
        $awayPlayers = $this->mapPlayers($dto->getAwayTeam(), false, $dto->getMatchId());

        return $this->lineupProfile->setLineup($lineup)
            ->setPlayers(array_values(array_merge($homePlayers, $awayPlayers)));
    }

    private function mapPlayers(?TeamDto $teamDto, bool $isHomeTeam, string $matchId): array
    {
        if (!is_null($teamDto) && !is_null($teamDto->getPlayers())) {
            return array_map(function ($dto) use ($isHomeTeam, $matchId) {
                return $this->lineupPlayerFactory->setEmpty()
                    ->setMatchId($matchId)
                    ->setTypeId($dto->getTypeId())
                    ->setPlayerId($dto->getPlayerId())
                    ->setPositionX($dto->getPositionX())
                    ->setPositionY($dto->getPositionY())
                    ->setShirtNumber($dto->getShirtNumber())
                    ->setHomeTeam($isHomeTeam)
                    ->create();
            }, $teamDto->getPlayers());
        } else {
            return [];
        }
    }
}