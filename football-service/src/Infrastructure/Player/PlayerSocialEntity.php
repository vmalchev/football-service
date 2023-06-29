<?php

namespace Sportal\FootballApi\Infrastructure\Player;

use JsonSerializable;
use Sportal\FootballApi\Application\Player\Dto\PlayerEditSocialDto;
use Sportal\FootballApi\Domain\Player\IPlayerSocialEntity;

class PlayerSocialEntity implements IPlayerSocialEntity, JsonSerializable
{
    private ?string $web;
    private ?string $twitter_id;
    private ?string $facebook_id;
    private ?string $instagram_id;
    private ?string $wikipedia_id;
    private ?string $youtube_channel_id;

    public function __construct(
        ?string $web,
        ?string $twitter_id,
        ?string $facebook_id,
        ?string $instagram_id,
        ?string $wikipedia_id,
        ?string $youtube_channel_id
    )
    {
        $this->web = $web;
        $this->twitter_id = $twitter_id;
        $this->facebook_id = $facebook_id;
        $this->instagram_id = $instagram_id;
        $this->wikipedia_id = $wikipedia_id;
        $this->youtube_channel_id = $youtube_channel_id;
    }

    public static function fromPlayerSocialDto(PlayerEditSocialDto $playerSocialDto): PlayerSocialEntity
    {
        return new PlayerSocialEntity(
            $playerSocialDto->getWeb(),
            $playerSocialDto->getTwitterId(),
            $playerSocialDto->getFacebookId(),
            $playerSocialDto->getInstagramId(),
            $playerSocialDto->getWikipediaId(),
            $playerSocialDto->getYoutubeChannelId()
        );
    }

    public static function createPlayerSocial(array $properties): IPlayerSocialEntity
    {
        $playerSocialChannel = new PlayerSocialEntity(null, null, null, null, null, null);

        foreach ($properties as $propertyName => $propertyValue) {
            $playerSocialChannel->{$propertyName} = $propertyValue;
        }

        return $playerSocialChannel;
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
     * @return string|null
     */
    public function getYoutubeChannelId(): ?string
    {
        return $this->youtube_channel_id;
    }

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this), fn($property) => !is_null($property));
    }

}