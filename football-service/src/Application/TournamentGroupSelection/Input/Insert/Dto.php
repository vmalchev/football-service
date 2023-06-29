<?php

namespace Sportal\FootballApi\Application\TournamentGroupSelection\Input\Insert;

use App\Validation\Identifier;
use Sportal\FootballApi\Application\IDto;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Sequentially;
use Symfony\Component\Validator\Constraints\Type;

/**
 * @SWG\Definition(definition="v2_TournamentGroupSelection")
 */
class Dto implements IDto, \JsonSerializable
{

    /**
     * @var string
     * @SWG\Property(property="match_id")
     */
    private string $match_id;

    public function __construct(string $match_id)
    {
        $this->match_id = $match_id;
    }

    /**
     * @return string
     */
    public function getMatchId(): string
    {
        return $this->match_id;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public static function getValidatorConstraints(): Collection {
        return new Collection([
            'match_id' => new Sequentially([
                new NotBlank(['allowNull' => false]),
                new Type(['type' => ['digit', 'numeric']]),
                new Identifier()
            ])
        ]);
    }

}