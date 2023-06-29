<?php


namespace Sportal\FootballApi\Application\PlayerStatistic\Input\Upsert;


use App\Validation\Identifier;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Domain\PlayerStatistic\OriginType;
use Symfony\Component\Validator\Constraints as Assert;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_PlayerStatisticInput")
 */
class Dto implements IDto, \JsonSerializable
{
    /**
     * @var string
     * @SWG\Property(property="player_id")
     */
    private string $player_id;

    /**
     * @var string
     * @SWG\Property(property="match_id")
     */
    private string $match_id;

    /**
     * @var string
     * @SWG\Property(property="team_id")
     */
    private string $team_id;

    /**
     * @var StatisticItem[]
     * @SWG\Property(property="statistics")
     */
    private array $statistics;

    /**
     * @var string
     * @SWG\Property(property="origin")
     */
    private string $origin;

    /**
     * @param string $player_id
     * @param string $match_id
     * @param string $team_id
     * @param StatisticItem[] $statistics
     * @param string $origin
     */
    public function __construct(
        string $player_id,
        string $match_id,
        string $team_id,
        array $statistics,
        string $origin
    ) {
        $this->player_id = $player_id;
        $this->match_id = $match_id;
        $this->team_id = $team_id;
        $this->statistics = $statistics;
        $this->origin = $origin;
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
    public function getMatchId(): string
    {
        return $this->match_id;
    }

    /**
     * @return string
     */
    public function getTeamId(): string
    {
        return $this->team_id;
    }

    /**
     * @return StatisticItem[]
     */
    public function getStatistics(): array
    {
        return $this->statistics;
    }

    /**
     * @return string
     */
    public function getOrigin(): string
    {
        return $this->origin;
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
                'match_id' => [
                    new Assert\NotBlank(),
                    new Assert\Type(['type' => ['digit', 'numeric']]),
                    new Identifier(),
                ],
                'team_id' => [
                    new Assert\NotBlank(),
                    new Assert\Type(['type' => ['digit', 'numeric']]),
                    new Identifier(),
                ],
                'statistics' => new Assert\Required(
                    [
                        new Assert\Type('array'),
                        new Assert\Unique(),
                        new Assert\All(StatisticItem::getValidatorConstraints())
                    ]
                ),
                'origin' => [
                    new Assert\NotBlank(),
                    new Assert\Choice(
                        [
                            'choices' => array_values(OriginType::keys()),
                            'message' => 'Choose a valid origin type. Options are: ' . implode(", ", OriginType::keys()),
                        ]
                    ),
                ]
            ]
        );
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}