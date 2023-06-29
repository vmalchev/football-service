<?php
namespace Sportal\FootballApi\Infrastructure\Entity;

use Sportal\FootballApi\Domain\Odd\IApplicationLink;

class ApplicationLinkEntity implements IApplicationLink, \JsonSerializable
{

    private $applicationId;

    private $betslipLink;

    private $fallbackLink;

    private $homepageLink;

    private $betslipCallbackUrl;

    public function __construct($applicationId, $betslipLink, $fallbackLink, $homepageLink=null, $betslipCallbackUrl=null)
    {
        $this->applicationId = $applicationId;
        $this->betslipLink = $betslipLink;
        $this->fallbackLink = $fallbackLink;

        $this->homepageLink = $homepageLink;
        $this->betslipCallbackUrl = $betslipCallbackUrl;
    }

    /**
     * @return mixed
     */
    public function getHomepageLink()
    {
        return $this->homepageLink;
    }

    /**
     * @param mixed $homepageLink
     */
    public function setHomepageLink($homepageLink)
    {
        $this->homepageLink = $homepageLink;
    }

    /**
     * @return mixed
     */
    public function getBetslipCallbackUrl()
    {
        return $this->betslipCallbackUrl;
    }

    /**
     * @param mixed $betslipCallbackUrl
     */
    public function setBetslipCallbackUrl($betslipCallbackUrl)
    {
        $this->betslipCallbackUrl = $betslipCallbackUrl;
    }

    /**
     * @return mixed
     */
    public function getApplicationId()
    {
        return $this->applicationId;
    }

    /**
     * @return mixed
     */
    public function getBetslipLink()
    {
        return $this->betslipLink;
    }

    /**
     * @return mixed
     */
    public function getFallbackLink()
    {
        return $this->fallbackLink;
    }

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this), function ($value) {
            return $value !== null;
        });
    }

    public static function create(array $data): IApplicationLink
    {
        return new ApplicationLinkEntity(
            $data['applicationId'],
            $data['betslipLink'] ?? null,
            $data['fallbackLink'],
            $data['homepageLink'] ?? null,
            $data['betslipCallbackUrl'] ?? null
        );
    }
}