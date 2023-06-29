<?php


namespace Sportal\FootballApi\Infrastructure\Player;


use Sportal\FootballApi\Domain\Player\IPlayerCollection;
use Sportal\FootballApi\Domain\Player\IPlayerEntity;

class PlayerCollection implements IPlayerCollection
{
    /**
     * @var IPlayerEntity[]
     */
    private array $players;

    /**
     * PlayerCollection constructor.
     * @param IPlayerEntity[] $players
     */
    public function __construct(array $players)
    {
        $this->players = [];
        foreach ($players as $player) {
            $this->players[$player->getId()] = $player;
        }
    }


    public function getAll(): array
    {
        return array_values($this->players);
    }

    public function getById(string $id): ?IPlayerEntity
    {
        return $this->players[$id] ?? null;
    }
}