<?php


namespace Sportal\FootballApi\Application\Player\Dto;

use JsonSerializable;

/**
 * @SWG\Definition()
 */
class PlayerEditSocialDto implements JsonSerializable
{
    /**
     * @var string|null
     * @SWG\Property()
     */
    private $web;

    /**
     * @var string|null
     * @SWG\Property()
     */
    private $twitter_id;

    /**
     * @var string|null
     * @SWG\Property()
     */
    private $facebook_id;

    /**
     * @var string|null
     * @SWG\Property()
     */
    private $instagram_id;

    /**
     * @var string|null
     * @SWG\Property()
     */
    private $wikipedia_id;

    /**
     * @var string|null
     * @SWG\Property()
     */
    private $youtube_channel_id;

    public function __construct(
        ?string $web = null,
        ?string $twitter_id = null,
        ?string $facebook_id = null,
        ?string $instagram_id = null,
        ?string $wikipedia_id = null,
        ?string $youtube_channel_id = null
    )
    {
        $this->web = $web;
        $this->twitter_id = $twitter_id;
        $this->facebook_id = $facebook_id;
        $this->instagram_id = $instagram_id;
        $this->wikipedia_id = $wikipedia_id;
        $this->youtube_channel_id = $youtube_channel_id;
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

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this), fn($property) => !is_null($property));
    }
}