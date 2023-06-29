<?php


namespace Sportal\FootballApi\Application\Season\Input\ListAll;


use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Domain\Season\SeasonStatus;


class Dto implements IDto
{
    /**
     * @var string|null
     */
    private ?string $tournamentId;

    /**
     * @var string|null
     */
    private ?string $teamId;

    /**
     * @var SeasonStatus|null
     */
    private ?SeasonStatus $status;

    /**
     * @param string|null $tournamentId
     * @param string|null $teamId
     * @param SeasonStatus|null $status
     */
    public function __construct(?string $tournamentId = null,
                                ?string $teamId = null,
                                ?SeasonStatus $status = null)
    {
        $this->tournamentId = $tournamentId;
        $this->teamId = $teamId;
        $this->status = $status;
    }

    /**
     * @return string|null
     */
    public function getTournamentId(): ?string
    {
        return $this->tournamentId;
    }

    /**
     * @return string|null
     */
    public function getTeamId(): ?string
    {
        return $this->teamId;
    }

    /**
     * @return SeasonStatus|null
     */
    public function getStatus(): ?SeasonStatus
    {
        return $this->status;
    }
}