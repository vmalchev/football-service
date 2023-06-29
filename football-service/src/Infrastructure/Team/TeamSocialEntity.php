<?php


namespace Sportal\FootballApi\Infrastructure\Team;


use JsonSerializable;
use Sportal\FootballApi\Application\Team\Dto\TeamSocialDto;
use Sportal\FootballApi\Domain\Team\ITeamSocialEntity;

class TeamSocialEntity implements JsonSerializable, ITeamSocialEntity
{

    private ?string $web;
    private ?string $twitter_id;
    private ?string $facebook_id;
    private ?string $instagram_id;
    private ?string $wikipedia_id;

    /**
     * TeamSocialEntity constructor.
     * @param string|null $web
     * @param string|null $twitter_id
     * @param string|null $facebook_id
     * @param string|null $instagram_id
     * @param string|null $wikipedia_id
     */
    public function __construct(?string $web,
                                ?string $twitter_id,
                                ?string $facebook_id,
                                ?string $instagram_id,
                                ?string $wikipedia_id)
    {
        $this->web = $web;
        $this->twitter_id = $twitter_id;
        $this->facebook_id = $facebook_id;
        $this->instagram_id = $instagram_id;
        $this->wikipedia_id = $wikipedia_id;
    }

    public static function fromArray(array $properties): ITeamSocialEntity
    {
        $teamSocialEntity = new TeamSocialEntity(null, null, null, null, null);

        foreach ($properties as $propertyName => $propertyValue) {
            $teamSocialEntity->{$propertyName} = $propertyValue;
        }

        return $teamSocialEntity;
    }

    /**
     * @return TeamSocialEntity
     */
    public static function fromTeamSocialDto(TeamSocialDto $entity)
    {
        return new TeamSocialEntity(
            $entity->getWeb(),
            $entity->getTwitterId(),
            $entity->getFacebookId(),
            $entity->getInstagramId(),
            $entity->getWikipediaId()
        );
    }

    /**
     * @return string|null
     */
    public function getWeb(): ?string
    {
        return $this->web;
    }

    /**
     * @return string|null
     */
    public function getTwitterId(): ?string
    {
        return $this->twitter_id;
    }

    /**
     * @return string|null
     */
    public function getFacebookId(): ?string
    {
        return $this->facebook_id;
    }

    /**
     * @return string|null
     */
    public function getInstagramId(): ?string
    {
        return $this->instagram_id;
    }

    /**
     * @return string|null
     */
    public function getWikipediaId(): ?string
    {
        return $this->wikipedia_id;
    }

    /**
     * @return array|mixed
     */
    public function JsonSerialize()
    {
        $vars = array_filter(
            get_object_vars($this),
            function ($item) {
                // Keep only not-NULL values
                return !is_null($item);
            }
        );

        return $vars;
    }


}