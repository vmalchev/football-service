<?php


namespace Sportal\FootballApi\Application\Player\Output\Get\Teams;

/**
 * @SWG\Definition(definition="v2_PlayerActiveClub")
 */
class Dto implements \JsonSerializable
{

    /**
     * @var \Sportal\FootballApi\Application\Team\Output\Get\Dto
     * @SWG\Property(property="team")
     */
    private \Sportal\FootballApi\Application\Team\Output\Get\Dto $team;

    /**
     * @var string
     * @SWG\Property(property="contract_type")
     */
    private string $contract_type;

    /**
     * @var string|null
     * @SWG\Property(property="start_date")
     */
    private ?string $start_date;

    /**
     * @var string|null
     * @SWG\Property(property="shirt_number")
     */
    private ?string $shirt_number;

    /**
     * @var string
     * @SWG\Property(enum=TEAM_SQUAD_STATUS,property="status")
     */
    private string $status;

    /**
     * Dto constructor.
     * @param \Sportal\FootballApi\Application\Team\Output\Get\Dto $team
     * @param string $contract_type
     * @param string|null $start_date
     * @param string|null $shirt_number
     * @param string $status
     */
    public function __construct(\Sportal\FootballApi\Application\Team\Output\Get\Dto $team,
                                string $contract_type,
                                ?string $start_date,
                                ?string $shirt_number,
                                string $status)
    {
        $this->team = $team;
        $this->contract_type = $contract_type;
        $this->start_date = $start_date;
        $this->shirt_number = $shirt_number;
        $this->status = $status;
    }

    /**
     * @return \Sportal\FootballApi\Application\Team\Output\Get\Dto
     */
    public function getTeam():\Sportal\FootballApi\Application\Team\Output\Get\Dto
    {
        return $this->team;
    }

    /**
     * @return string
     */
    public function getContractType(): string
    {
        return $this->contract_type;
    }

    /**
     * @return string|null
     */
    public function getStartDate(): ?string
    {
        return $this->start_date;
    }

    /**
     * @return string|null
     */
    public function getShirtNumber(): ?string
    {
        return $this->shirt_number;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}