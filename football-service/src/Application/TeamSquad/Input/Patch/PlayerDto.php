<?php


namespace Sportal\FootballApi\Application\TeamSquad\Input\Patch;

use App\Validation\Identifier;
use Sportal\FootballApi\Domain\TeamSquad\PlayerContractType;
use Sportal\FootballApi\Domain\TeamSquad\TeamSquadStatus;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @SWG\Definition(definition="v2_SquadPlayerInput")
 */
class PlayerDto
{
    /**
     * @SWG\Property(property="player_id")
     * @var string
     */
    private string $player_id;

    /**
     * @SWG\Property(enum=TEAM_SQUAD_STATUS, property="status")
     * @var string
     */
    private string $status;

    /**
     * @SWG\Property(enum=PLAYER_CONTRACT_TYPE, property="contract_type")
     * @var string|null
     */
    private ?string $contract_type;

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
     * PlayerDto constructor.
     * @param string $player_id
     * @param string $status
     * @param string|null $contract_type
     * @param string|null $start_date
     * @param string|null $end_date
     * @param int|null $shirt_number
     */
    public function __construct(string $player_id,
                                string $status,
                                ?string $contract_type = null,
                                ?string $start_date = null,
                                ?string $end_date = null,
                                ?int $shirt_number = null)
    {
        $this->player_id = $player_id;
        $this->status = $status;
        $this->contract_type = $contract_type;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->shirt_number = $shirt_number;
    }


    /**
     * @return string
     */
    public function getPlayerId(): string
    {
        return $this->player_id;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string|null
     */
    public function getContractType(): ?string
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

    public static function getValidatorConstraints(): Assert\Collection
    {
        return new Assert\Collection(
            [
                'player_id' => [
                    new Assert\NotBlank(),
                    new Assert\Type(['type' => ['digit', 'numeric']]),
                    new Identifier(),
                ],
                'status' => [
                    new Assert\NotBlank(),
                    new Assert\Choice([
                        'choices' => array_values(TeamSquadStatus::keys()),
                        'message' => 'Choose a valid status. Options are: ' . implode(", ", TeamSquadStatus::keys()),
                    ])
                ],
                'contract_type' => new Assert\Optional([
                    new Assert\Choice([
                        'choices' => array_values(PlayerContractType::keys()),
                        'message' => 'Choose a valid position. Options are: ' . implode(", ", PlayerContractType::keys())
                    ])
                ]),
                'start_date' => new Assert\Optional([new Assert\Date()]),
                'end_date' => new Assert\Optional([new Assert\Date()]),
                'shirt_number' => new Assert\Optional([
                    new Assert\Type(['type' => 'integer']),
                    new Assert\Positive()
                ])
            ]
        );
    }
}