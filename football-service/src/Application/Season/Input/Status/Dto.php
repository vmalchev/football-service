<?php


namespace Sportal\FootballApi\Application\Season\Input\Status;


use App\Validation\Identifier;
use Sportal\FootballApi\Application\IDto;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @SWG\Definition(definition="v2_TournamentSeasonStatusInput")
 */
class Dto implements IDto, \JsonSerializable
{

    /**
     * @SWG\Property(property="season_id")
     * @var string
     */
    private string $seasonId;

    private string $tournamentId;

    public function __construct(string $seasonId)
    {
        $this->seasonId = $seasonId;
    }

    /**
     * @return string
     */
    public function getTournamentId(): string
    {
        return $this->tournamentId;
    }

    /**
     * @return string
     */
    public function getSeasonId(): string
    {
        return $this->seasonId;
    }

    /**
     * @param string $tournamentId
     */
    public function setTournamentId(string $tournamentId): void
    {
        $this->tournamentId = $tournamentId;
    }

    public static function getValidatorConstraints(): Assert\Collection
    {
        return new Assert\Collection([
           'season_id' => [
               new Assert\NotBlank(),
               new Assert\Type(['type' => ['digit', 'numeric']]),
               new Identifier()
           ]
        ]);
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}