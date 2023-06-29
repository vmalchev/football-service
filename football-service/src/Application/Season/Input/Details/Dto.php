<?php

namespace Sportal\FootballApi\Application\Season\Input\Details;

use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Domain\Season\SeasonStatus;

class Dto implements IDto
{

    /**
     * @var string|null
     */
    private ?string $seasonId;

    /**
     * @var string|null
     */
    private ?string $tournamentId;

    /**
     * @var SeasonStatus
     */
    private SeasonStatus $status;

    /**
     * @param string|null $seasonId
     * @param string|null $tournamentId
     * @param SeasonStatus $status
     */
    public function __construct(?string $seasonId, ?string $tournamentId, SeasonStatus $status)
    {
        $this->seasonId = $seasonId;
        $this->tournamentId = $tournamentId;
        $this->status = $status;
    }

    /**
     * @return string|null
     */
    public function getSeasonId(): ?string
    {
        return $this->seasonId;
    }

    /**
     * @return string|null
     */
    public function getTournamentId(): ?string
    {
        return $this->tournamentId;
    }

    /**
     * @return SeasonStatus
     */
    public function getStatus(): SeasonStatus
    {
        return $this->status;
    }

}