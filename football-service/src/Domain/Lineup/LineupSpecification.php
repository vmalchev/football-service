<?php


namespace Sportal\FootballApi\Domain\Lineup;


class LineupSpecification
{

    private const START_PLAYERS_MIN = 7;
    private const START_PLAYERS_MAX = 11;
    private const MIN_X = 1;
    private const MAX_X = 11;
    private const MIN_Y = 1;
    private const MAX_Y = 9;

    /**
     * @param ILineupProfile $lineupProfile
     * @throws InvalidLineupException
     */
    public function validate(ILineupProfile $lineupProfile)
    {
        $this->validateTeam($lineupProfile->getHomePlayers(), $lineupProfile->getLineup()->getHomeTeamFormation());
        $this->validateTeam($lineupProfile->getAwayPlayers(), $lineupProfile->getLineup()->getAwayTeamFormation());

        $keys = array_map(fn($player) => $player->getMatchId() . $player->getPlayerId() . $player->getPlayerName(), $lineupProfile->getPlayers());
        if (count(array_unique($keys)) !== count($keys)) {
            throw new InvalidLineupException("Lineup contains duplicate players");
        }
    }

    /**
     * @param ILineupPlayerEntity[] $lineupPlayers
     * @param string|null $formation
     * @throws InvalidLineupException
     */
    private function validateTeam(array $lineupPlayers, ?string $formation)
    {
        if (!empty($lineupPlayers)) {
            $startingPlayers = array_filter($lineupPlayers, fn($player) => $player->getType()->getCategory() == "start");
            $this->validateStarters($startingPlayers);
            if (!empty($formation)) {
                foreach ($startingPlayers as $player) {
                    $this->validatePosition($player);
                }
            }
        } else if (!empty($formation)) {
            throw new InvalidLineupException("Formation has with no players");
        }
    }

    /**
     * @param array $startingPlayers
     * @throws InvalidLineupException
     */
    private function validateStarters(array $startingPlayers)
    {
        if (count($startingPlayers) < self::START_PLAYERS_MIN || count($startingPlayers) > self::START_PLAYERS_MAX) {
            throw new InvalidLineupException("Starting lineup players should be between(" . self::START_PLAYERS_MIN . "," . self::START_PLAYERS_MAX . ")");
        }
    }

    /**
     * @param ILineupPlayerEntity $player
     * @throws InvalidLineupException
     */
    private function validatePosition(ILineupPlayerEntity $player)
    {
        if (!($player->getPositionX() >= self::MIN_X && $player->getPositionX() <= self::MAX_X)) {
            throw new InvalidLineupException("Invalid lineup player position: {$player->getPositionX()}, {$player->getPositionY()}");
        }
        if (!($player->getPositionY() >= self::MIN_Y && $player->getPositionY() <= self::MAX_Y)) {
            throw new InvalidLineupException("Invalid lineup player position: {$player->getPositionX()}, {$player->getPositionY()}");
        }
    }
}