<?php


namespace Sportal\FootballApi\Domain\Referee;


class RefereeFilter
{
    /**
     * @var string[]
     */
    private ?array $seasonIds;

    /**
     * RefereeFilter constructor.
     * @param string[]|null $seasonIds
     */
    public function __construct(?array $seasonIds)
    {
        $this->seasonIds = $seasonIds;
    }

    /**
     * @return string[]
     */
    public function getSeasonIds(): ?array
    {
        return $this->seasonIds;
    }
}