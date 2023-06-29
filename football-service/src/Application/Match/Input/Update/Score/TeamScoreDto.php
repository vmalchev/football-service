<?php


namespace Sportal\FootballApi\Application\Match\Input\Update\Score;

use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @SWG\Definition(definition="v2_TeamScoreInput")
 */
class TeamScoreDto
{
    /**
     * @SWG\Property(property="home")
     * @var int
     */
    private int $home;


    /**
     * @SWG\Property(property="away")
     * @var int
     */
    private int $away;

    /**
     * TeamScore constructor.
     * @param int $home
     * @param int $away
     */
    public function __construct(int $home, int $away)
    {
        $this->home = $home;
        $this->away = $away;
    }

    /**
     * @return int
     */
    public function getHome(): int
    {
        return $this->home;
    }

    /**
     * @return int
     */
    public function getAway(): int
    {
        return $this->away;
    }

    public static function getValidatorConstraints(): Assert\Collection
    {
        return new Assert\Collection([
            'home' => [
                new Assert\NotBlank(),
                new Assert\Type(['type' => ['int']]),
                new Assert\GreaterThanOrEqual(0)
            ],
            'away' => [
                new Assert\NotBlank(),
                new Assert\Type(['type' => ['int']]),
                new Assert\GreaterThanOrEqual(0)
            ]
        ]);
    }

}