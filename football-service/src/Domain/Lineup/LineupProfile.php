<?php


namespace Sportal\FootballApi\Domain\Lineup;


class LineupProfile implements ILineupProfile
{
    private ILineupEntity $lineup;

    /**
     * @var ILineupPlayerEntity[]
     */
    private array $players;

    public function setLineup(ILineupEntity $lineupEntity): ILineupProfile
    {
        $profile = clone $this;
        $profile->lineup = $lineupEntity;
        return $profile;
    }

    public function getLineup(): ILineupEntity
    {
        return $this->lineup;
    }

    /**
     * @inheritDoc
     */
    public function setPlayers(array $players): ILineupProfile
    {
        $profile = clone $this;
        $profile->players = $players;
        // all players must have a type for sorting to work
        if (empty(array_filter($profile->players, fn($player) => is_null($player->getType())))) {
            usort($profile->players, [$this, 'playerComparator']);
        }
        return $profile;
    }

    /**
     * @inheritDoc
     */
    public function getHomePlayers(): array
    {
        return array_values(array_filter($this->players, fn($player) => $player->getHomeTeam()));
    }

    /**
     * @inheritDoc
     */
    public function getAwayPlayers(): array
    {
        return array_values(array_filter($this->players, fn($player) => !$player->getHomeTeam()));
    }

    /**
     * @inheritDoc
     */
    public function getPlayers(): array
    {
        return $this->players;
    }

    public function hasHomeTeam(): bool
    {
        return (!is_null($this->lineup->getHomeTeamFormation())
            || !is_null($this->lineup->getHomeCoach())
            || !empty($this->getHomePlayers()));
    }

    public function hasAwayTeam(): bool
    {
        return (!is_null($this->lineup->getAwayTeamFormation())
            || !is_null($this->lineup->getAwayCoach())
            || !empty($this->getAwayPlayers()));

    }

    private function playerComparator(ILineupPlayerEntity $playerA, ILineupPlayerEntity $playerB): int
    {
        $sort = (int)$playerA->getHomeTeam() - (int)$playerB->getHomeTeam();
        if ($sort === 0) {
            $sort = $playerA->getType()->getSortOrder() - $playerB->getType()->getSortOrder();
            if ($sort === 0) {
                $sort = (int)($playerA->getPositionX() - $playerB->getPositionX());
                if ($sort === 0) {
                    $sort = (int)($playerA->getPositionY() - $playerB->getPositionY());
                }
            }
        }
        return $sort;
    }
}