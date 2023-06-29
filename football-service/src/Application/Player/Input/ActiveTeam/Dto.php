<?php


namespace Sportal\FootballApi\Application\Player\Input\ActiveTeam;


use Sportal\FootballApi\Domain\TeamSquad\PlayerContractType;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @SWG\Definition(definition="v2_ActiveTeamInput")
 */
class Dto implements \JsonSerializable
{

    /**
     * @var string
     * @SWG\Property(property="team_id")
     */
    private string $team_id;

    /**
     * @var string|null
     * @SWG\Property(property="contract_type")
     */
    private ?string $contract_type;

    /**
     * @var string|null
     * @SWG\Property(property="start_date")
     */
    private ?string $start_date;

    /**
     * @var int|null
     * @SWG\Property(property="shirt_number")
     */
    private ?int $shirt_number;

    /**
     * Dto constructor.
     * @param string $team_id
     * @param string|null $contract_type
     * @param string|null $start_date
     * @param int|null $shirt_number
     */
    public function __construct(string $team_id, ?string $contract_type, ?string $start_date, ?int $shirt_number)
    {
        $this->team_id = $team_id;
        $this->contract_type = $contract_type;
        $this->start_date = $start_date;
        $this->shirt_number = $shirt_number;
    }

    /**
     * @return string
     */
    public function getTeamId(): string
    {
        return $this->team_id;
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
     * @return int|null
     */
    public function getShirtNumber(): ?int
    {
        return $this->shirt_number;
    }

    public static function getValidatorConstraints(): Assert\Collection
    {
        return new Assert\Collection([
            'team_id' => [
                new Assert\NotBlank(),
                new Assert\Type(['type' => ['digit', 'numeric']])
            ],
            'contract_type' => new Assert\Optional([
                new Assert\Choice([
                    'choices' => array_values(PlayerContractType::keys()),
                    'message' => 'Choose a valid position. Options are: ' . implode(", ", PlayerContractType::keys())
                ])
            ]),
            'start_date' => new Assert\Optional([new Assert\Type(['type' => 'string'])]),
            'shirt_number' => new Assert\Optional([new Assert\Type(['type' => ['digit', 'numeric']])])
        ]);
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}