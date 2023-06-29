<?php


namespace Sportal\FootballApi\Application\StageTeam\Input\Update;


use App\Validation\Identifier;
use Sportal\FootballApi\Application\IDto;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @SWG\Definition(definition="v2_StageTeamInput")
 */
class Dto implements IDto
{

    /**
     * @var string
     * @SWG\Property(property="team_id")
     */
    private string $team_id;

    public function __construct(string $team_id)
    {
        $this->team_id = $team_id;
    }

    public function getTeamId(): string
    {
        return $this->team_id;
    }

    public static function getValidatorConstraints(): Assert\Collection
    {
        return new Assert\Collection([
            'team_id' => [
                new Assert\NotBlank(),
                new Assert\Type(['type' => ['digit', 'numeric']]),
                new Identifier()
            ]
        ]);
    }

}