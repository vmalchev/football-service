<?php


namespace Sportal\FootballApi\Application\Team\Dto;

use JsonSerializable;

/**
 * @SWG\Definition()
 */
class TeamSocialDto implements JsonSerializable
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
     * TeamSocialDto constructor.
     * @param string|null $web
     * @param string|null $twitter_id
     * @param string|null $facebook_id
     * @param string|null $instagram_id
     * @param string|null $wikipedia_id
     */
    public function __construct(
        ?string $web = null,
        ?string $twitter_id = null,
        ?string $facebook_id = null,
        ?string $instagram_id = null,
        ?string $wikipedia_id = null
    ) {
        $this->web = $web;
        $this->twitter_id = $twitter_id;
        $this->facebook_id = $facebook_id;
        $this->instagram_id = $instagram_id;
        $this->wikipedia_id = $wikipedia_id;
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
    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this), fn($property) => !is_null($property));
    }


}