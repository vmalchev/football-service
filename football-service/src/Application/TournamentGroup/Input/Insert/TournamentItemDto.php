<?php

namespace Sportal\FootballApi\Application\TournamentGroup\Input\Insert;

use App\Validation\Identifier;
use Sportal\FootballApi\Application\IDto;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @SWG\Definition
 */
class TournamentItemDto implements IDto, \JsonSerializable
{

    /**
     * @var string
     * @SWG\Property(property="tournament_id")
     */
    private string $tournament_id;

    /**
     * @var int
     * @SWG\Property(property="sort_order")
     */
    private int $sort_order;

    public function __construct(string $tournament_id, int $sort_order)
    {
        $this->tournament_id = $tournament_id;
        $this->sort_order = $sort_order;
    }

    public function getSortOrder(): int
    {
        return $this->sort_order;
    }

    public function getTournamentId(): string
    {
        return $this->tournament_id;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public static function getValidatorConstraints(): Assert\Collection
    {
        return new Assert\Collection([
            'tournament_id' => [
                new Assert\NotBlank(),
                new Assert\Type(['digit', 'numeric']),
                new Identifier()
            ],
            'sort_order' => [
                new Assert\NotBlank(),
                new Assert\Type('integer'),
                new Assert\Positive(),
                new Identifier()
            ]
        ]);
    }

}