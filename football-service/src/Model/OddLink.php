<?php
namespace Sportal\FootballApi\Model;

use Sportal\FootballApi\Domain\Odd\IApplicationLink;
use Sportal\FootballApi\Infrastructure\Entity\ApplicationLinkEntity;

class OddLink implements ModelInterface
{

    /**
     *
     * @var integer
     */
    private $oddProviderId;

    /**
     *
     * @var \Sportal\FootballApi\Model\OddClient
     */
    private $oddClient;

    /**
     *
     * @var string
     */
    private $oddslipLink;

    /**
     *
     * @var string
     */
    private $fallbackLink;


    /**
     * @var string
     */
    private $links;

    /**
     *
     * @var string
     */
    private $sortorder;

    /**
     * @var OddProvider
     */
    private $oddProvider;

    /**
     * @return OddProvider
     */
    public function getOddProvider()
    {
        return $this->oddProvider;
    }

    /**
     * @param OddProvider $oddProvider
     */
    public function setOddProvider(OddProvider $oddProvider)
    {
        $this->oddProvider = $oddProvider;
        return $this;
    }


    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPersistanceMap()
     */
    public function getPersistanceMap()
    {
        return [
            'odd_provider_id' => $this->oddProviderId,
            'odd_client_id' => $this->oddClient->getId(),
            'oddslip_link' => $this->oddslipLink,
            'fallback_link' => $this->fallbackLink,
            'sortorder' => $this->sortorder,
            'links' => !empty($this->links) ? json_encode($this->links) : null
        ];
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPrimaryKeyMap()
     */
    public function getPrimaryKeyMap()
    {
        return [
            'odd_provider_id' => $this->oddProviderId,
            'odd_client_id' => $this->oddClient->getId()
        ];
    }

    public function getOddProviderId()
    {
        return $this->oddProviderId;
    }

    public function setOddProviderId($oddProviderId)
    {
        $this->oddProviderId = $oddProviderId;
        return $this;
    }

    public function getOddClient()
    {
        return $this->oddClient;
    }

    public function setOddClient(\Sportal\FootballApi\Model\OddClient $oddClient)
    {
        $this->oddClient = $oddClient;
        return $this;
    }

    public function getOddslipLink()
    {
        return $this->oddslipLink;
    }

    public function setOddslipLink($oddslipLink)
    {
        $this->oddslipLink = $oddslipLink;
        return $this;
    }

    public function getFallbackLink()
    {
        return $this->fallbackLink;
    }

    public function setFallbackLink($fallbackLink)
    {
        $this->fallbackLink = $fallbackLink;
        return $this;
    }

    public function getSortorder()
    {
        return $this->sortorder;
    }

    public function setSortorder($sortorder)
    {
        $this->sortorder = (int) $sortorder;
        return $this;
    }

    /**
     * @return IApplicationLink[]
     */
    public function getLinks()
    {
        if (empty($this->links)) {
            return [
                new ApplicationLinkEntity('default', $this->oddslipLink, $this->fallbackLink)
            ];
        } else {
            return $this->links;
        }
    }

    /**
     * @param string $links
     */
    public function setLinks($links)
    {
        $this->links = $links;
        return $this;
    }
}