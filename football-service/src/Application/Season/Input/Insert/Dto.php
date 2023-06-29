<?php


namespace Sportal\FootballApi\Application\Season\Input\Insert;


use App\Validation\Identifier;
use Sportal\FootballApi\Application\IDto;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @SWG\Definition(definition="v2_TournamentSeasonInsertInput")
 */
class Dto implements IDto
{

    /**
     * @SWG\Property(property="tournament_id")
     * @var string
     */
    private string $tournament_id;

    /**
     * @SWG\Property(property="name")
     * @var string
     */
    private string $name;

    public function __construct(string $name, string $tournament_id)
    {
        $this->name = $name;
        $this->tournament_id = $tournament_id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getTournamentId(): string
    {
        return $this->tournament_id;
    }

    public static function getValidatorConstraints(): Assert\Collection
    {
        return new Assert\Collection([
            'tournament_id' => [
                new Assert\NotBlank(),
                new Assert\Type(['type' => ['digit', 'numeric']]),
                new Identifier()
            ],
            'name' => [
                new Assert\NotBlank(),
                new Assert\Type(['type' => ['string']]),
                new Assert\Length(['max' => 20])
            ]
        ]);
    }

}