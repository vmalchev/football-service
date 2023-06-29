<?php


namespace Sportal\FootballApi\Application\Match\Input\Update\Referee;

use App\Validation\Identifier;
use Sportal\FootballApi\Domain\Match\MatchRefereeRole;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @SWG\Definition(definition="v2_MatchRefereeInput")
 */
class Dto
{
    /**
     * @SWG\Property(property="referee_id")
     * @var string
     */
    private string $referee_id;

    /**
     * @SWG\Property(property="role", enum=MATCH_REFEREE_ROLE)
     * @var string
     */
    private string $role;

    /**
     * Dto constructor.
     * @param string $referee_id
     * @param string $role
     */
    public function __construct(string $referee_id, string $role)
    {
        $this->referee_id = $referee_id;
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getRefereeId(): string
    {
        return $this->referee_id;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    public static function getValidatorConstraints(): Assert\Collection
    {
        return new Assert\Collection([
            'referee_id' => [
                new Assert\NotBlank(),
                new Assert\Type(['type' => ['digit', 'numeric']]),
                new Identifier(),
            ],
            'role' => [
                new Assert\NotBlank(),
                new Assert\Choice([
                    'choices' => MatchRefereeRole::keys(),
                    'message' => 'Choose a valid role. Options are: ' . implode(", ", MatchRefereeRole::keys()),
                ])
            ]
        ]);
    }

}