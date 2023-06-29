<?php

namespace Sportal\FootballApi\Application\Match\Input\ListLivescore;

use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Domain\Match\LivescoreSelectionFilter;
use Sportal\FootballApi\Domain\MatchStatus\MatchStatusType;

class Dto implements IDto
{

    /**
     * @var \DateTimeImmutable|null
     */
    private ?\DateTimeImmutable $date;

    /**
     * @var float|null
     */
    private ?float $utc_offset;

    /**
     * @var array|null
     */
    private ?array $match_ids;

    /**
     * @var string|null
     */
    private ?string $tournament_group;

    /**
     * @var MatchStatusType[]|null
     */
    private ?array $status_types;

    /**
     * @var LivescoreSelectionFilter|null
     */
    private ?LivescoreSelectionFilter $selection_filter;

    /**
     * @param \DateTimeImmutable|null $date
     * @param float|null $utc_offset
     * @param array|null $match_ids
     * @param string|null $tournament_group
     * @param MatchStatusType[]|null $status_types
     * @param LivescoreSelectionFilter|null $selection_filter
     */
    public function __construct(?\DateTimeImmutable $date,
                                ?float $utc_offset,
                                ?array $match_ids,
                                ?string $tournament_group,
                                ?array $status_types,
                                ?LivescoreSelectionFilter $selection_filter)
    {
        $this->date = $date;
        $this->utc_offset = $utc_offset;
        $this->match_ids = $match_ids;
        $this->tournament_group = $tournament_group;
        $this->status_types = $status_types;
        $this->selection_filter = $selection_filter;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return float|null
     */
    public function getUtcOffset(): ?float
    {
        return $this->utc_offset;
    }

    /**
     * @return array|null
     */
    public function getMatchIds(): ?array
    {
        return $this->match_ids;
    }

    /**
     * @return string|null
     */
    public function getTournamentGroup(): ?string
    {
        return $this->tournament_group;
    }

    /**
     * @return MatchStatusType[]|null
     */
    public function getStatusTypes(): ?array
    {
        return $this->status_types;
    }

    /**
     * @return LivescoreSelectionFilter|null
     */
    public function getSelectionFilter(): ?LivescoreSelectionFilter
    {
        return $this->selection_filter;
    }

}