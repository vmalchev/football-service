<?php


namespace Sportal\FootballApi\Infrastructure\Team;


use Sportal\FootballApi\Domain\Team\ITeamCollection;
use Sportal\FootballApi\Domain\Team\ITeamEntity;

class TeamCollection implements ITeamCollection
{
    private array $teams;

    public function __construct(array $teams)
    {
        $this->teams = [];
        foreach ($teams as $team) {
            $this->teams[$team->getId()] = $team;
        }
    }

    public function getAll(): array
    {
        return array_values($this->teams);
    }

    public function getById($id): ?ITeamEntity
    {
        return $this->teams[$id] ?? null;
    }

    public function getByIds($ids): self {
        $teams = array_filter($this->teams, function (ITeamEntity $team) use ($ids) {
           return in_array($team->getId(), $ids);
        });

        return new self($teams);
    }
}