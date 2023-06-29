<?php


namespace Sportal\FootballApi\Infrastructure\Season;


use Sportal\FootballApi\Domain\Season\ISeasonCollection;
use Sportal\FootballApi\Domain\Season\ISeasonEntity;

class SeasonCollection implements ISeasonCollection
{
    private array $seasons;

    public function __construct(array $seasons)
    {
        $this->seasons = [];
        foreach ($seasons as $season) {
            $this->seasons[$season->getId()] = $season;
        }
    }

    public function getAll(): array
    {
        return array_values($this->seasons);
    }

    public function getById($id): ?ISeasonEntity
    {
        return $this->seasons[$id] ?? null;
    }
}