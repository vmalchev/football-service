<?php


namespace Sportal\FootballApi\Application\MatchEvent\Input\Put;


use App\Validation\Identifier;
use Sportal\FootballApi\Domain\MatchEvent\MatchEventType;
use Sportal\FootballApi\Domain\MatchEvent\TeamPositionStatus;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @SWG\Definition(definition="v2_MatchEventInput")
 */
class MatchEventDto
{
    /**
     * @SWG\Property(property="id")
     * @var string|null
     */
    private ?string $id;

    /**
     * @SWG\Property(property="type_code", enum=MATCH_EVENT_TYPE)
     * @var string
     */
    private string $type_code;

    /**
     * @SWG\Property(property="team_position", enum=TEAM_POSITION_STATUS)
     * @var string
     */
    private string $team_position;

    /**
     * @SWG\Property(property="minute")
     * @var int
     */
    private int $minute;

    /**
     * @SWG\Property(property="primary_player_id")
     * @var string|null
     */
    private ?string $primary_player_id;

    /**
     * @SWG\Property(property="secondary_player_id")
     * @var string|null
     */
    private ?string $secondary_player_id;

    /**
     * @SWG\Property(property="sort_order", description="Property is used to determine the order of events, if two or more events have the same minute")
     * @var int|null
     */
    private ?int $sort_order;

    /**
     * MatchEventDto constructor.
     * @param string|null $id
     * @param string $type_code
     * @param string $team_position
     * @param int $minute
     * @param string|null $primary_player_id
     * @param string|null $secondary_player_id
     * @param int|null $sort_order
     */
    public function __construct(?string $id = null,
                                string $type_code,
                                string $team_position,
                                int $minute,
                                ?string $primary_player_id = null,
                                ?string $secondary_player_id = null,
                                ?int $sort_order = null)
    {
        $this->id = $id;
        $this->type_code = $type_code;
        $this->team_position = $team_position;
        $this->minute = $minute;
        $this->primary_player_id = $primary_player_id;
        $this->secondary_player_id = $secondary_player_id;
        $this->sort_order = $sort_order;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTypeCode(): string
    {
        return $this->type_code;
    }

    /**
     * @return string
     */
    public function getTeamPosition(): string
    {
        return $this->team_position;
    }

    /**
     * @return int
     */
    public function getMinute(): int
    {
        return $this->minute;
    }

    /**
     * @return string|null
     */
    public function getPrimaryPlayerId(): ?string
    {
        return $this->primary_player_id;
    }

    /**
     * @return string|null
     */
    public function getSecondaryPlayerId(): ?string
    {
        return $this->secondary_player_id;
    }

    /**
     * @return int|null
     */
    public function getSortOrder(): ?int
    {
        return $this->sort_order;
    }


    public static function getValidatorConstraints(): Assert\Collection
    {
        return new Assert\Collection([
            'id' => new Assert\Optional([
                new Assert\Type(['type' => ['digit', 'numeric']]),
                new Identifier(),
            ]),
            'type_code' => [
                new Assert\NotBlank(),
                new Assert\Choice([
                    'choices' => array_values(MatchEventType::keys()),
                    'message' => 'Choose a valid type code. Options are: ' . implode(", ", MatchEventType::keys())
                ])
            ],
            'team_position' => [
                new Assert\NotBlank(),
                new Assert\Choice([
                    'choices' => array_values(TeamPositionStatus::keys()),
                    'message' => 'Choose a valid team position. Options are: ' . implode(", ", TeamPositionStatus::keys())
                ])
            ],
            'minute' => [
                new Assert\NotBlank(),
                new Assert\Type('int'),
                new Assert\GreaterThanOrEqual(0)
            ],
            'primary_player_id' => new Assert\Optional([
                new Assert\NotBlank(),
                new Assert\Type(['type' => ['digit', 'numeric']]),
                new Identifier(),
            ]),
            'secondary_player_id' => new Assert\Optional([
                new Assert\NotBlank(),
                new Assert\Type(['type' => ['digit', 'numeric']]),
                new Identifier(),
            ]),
            'sort_order' => new Assert\Optional([
                new Assert\Type('int'),
                new Assert\GreaterThanOrEqual(0)
            ])
        ]);
    }

}