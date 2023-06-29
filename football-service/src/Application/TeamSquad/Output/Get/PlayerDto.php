<?php


namespace Sportal\FootballApi\Application\TeamSquad\Output\Get;


use JsonSerializable;
use Sportal\FootballApi\Application\Player;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_SquadPlayer", required={"player", "status"})
 */
class PlayerDto implements JsonSerializable
{
    /**
     * @SWG\Property(property="player")
     * @var Player\Output\Get\Dto
     */
    private Player\Output\Get\Dto $player;

    /**
     * @SWG\Property(enum=TEAM_SQUAD_STATUS, property="status")
     * @var string
     */
    private string $status;

    /**
     * @SWG\Property(enum=PLAYER_CONTRACT_TYPE, property="contract_type")
     * @var string
     */
    private string $contract_type;

    /**
     * @SWG\Property(property="start_date")
     * @var string|null
     */
    private ?string $start_date;

    /**
     * @SWG\Property(property="end_date")
     * @var string|null
     */
    private ?string $end_date;

    /**
     * @SWG\Property(property="shirt_number")
     * @var int|null
     */
    private ?int $shirt_number;

    /**
     * SquadPlayerDto constructor.
     * @param Player\Output\Get\Dto $player
     * @param string $status
     * @param string $contract_type
     * @param string|null $start_date
     * @param string|null $end_date
     * @param int|null $shirt_number
     */
    public function __construct(Player\Output\Get\Dto $player, string $status, string $contract_type, ?string $start_date, ?string $end_date, ?int $shirt_number)
    {
        $this->player = $player;
        $this->status = $status;
        $this->contract_type = $contract_type;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->shirt_number = $shirt_number;
    }

    /**
     * @return Player\Output\Get\Dto
     */
    public function getPlayer(): Player\Output\Get\Dto
    {
        return $this->player;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
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
    public function getEndDate(): ?string
    {
        return $this->end_date;
    }

    /**
     * @return int|null
     */
    public function getShirtNumber(): ?int
    {
        return $this->shirt_number;
    }


    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}