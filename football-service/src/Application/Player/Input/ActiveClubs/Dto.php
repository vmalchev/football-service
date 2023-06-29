<?php


namespace Sportal\FootballApi\Application\Player\Input\ActiveClubs;


use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @SWG\Definition(definition="v2_ActiveClubsInput")
 */
class Dto
{

    /**
     * @SWG\Property(property="clubs")
     * @var \Sportal\FootballApi\Application\Player\Input\ActiveTeam\Dto[]
     */
    private array $clubs;

    /**
     * @var string $playerId
     */
    private string $playerId;

    /**
     * Dto constructor.
     * @param \Sportal\FootballApi\Application\Player\Input\ActiveTeam\Dto[] $clubs
     * @param string $playerId
     */
    public function __construct(array $clubs)
    {
        $this->clubs = $clubs;
    }

    /**
     * @return \Sportal\FootballApi\Application\Player\Input\ActiveTeam\Dto[]
     */
    public function getClubs(): array
    {
        return $this->clubs;
    }

    /**
     * @return string
     */
    public function getPlayerId(): string
    {
        return $this->playerId;
    }

    public function setPlayerId(string $playerId): void
    {
        $this->playerId = $playerId;
    }

    public static function getValidatorConstraints(): Assert\Collection
    {
        return new Assert\Collection([
            'clubs' => new Assert\Required([
                new Assert\Type('array'),
                new Assert\All([\Sportal\FootballApi\Application\Player\Input\ActiveTeam\Dto::getValidatorConstraints()])
            ])
        ]);
    }
}